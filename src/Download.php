<?php

namespace Bhittani\Download;

use ZipArchive;
use RuntimeException;

class Download
{
    protected $url;

    protected $callback;

    public function __construct($zip, callable $callback = null)
    {
        $this->url = $zip;

        if ($callback) {
            $this->callback($callback);
        }
    }

    public function callback(callable $callback)
    {
        $this->callback = $callback;

        return $this;
    }

    public function to($path, array $options = [])
    {
        $path = rtrim($path, '\/');
        $parent = dirname($path);
        $archive = $path.'.zip';

        if (file_exists($path)) {
            throw new RuntimeException("Destination path [{$path}] already exists.");
        }

        file_put_contents($archive, $this->getContents($options));

        $name = $this->extract($archive, $parent);

        rename($parent.'/'.$name, $path);
    }

    protected function extract($archive, $dest)
    {
        $zip = new ZipArchive;
        $zip->open($archive);
        $zip->extractTo($dest);
        $binary = $zip->getNameIndex(0);
        $zip->close();

        unlink($archive);

        return $binary;
    }

    protected function getContents(array $options)
    {
        $options = array_merge([
            'ssl' => true,
            'proxy' => null,
        ], $options);

        $context = stream_context_create(array_filter([
            'http' => $options['proxy']
                ? [
                    'proxy' => $options['proxy'],
                    'request_fulluri' => true,
                ]
                : null,
            'ssl' => $options['ssl']
                ? null
                : [
                    'verify_peer' => false,
                ],
        ]), [
            'notification' => [$this, 'notifier'],
        ]);

        $archive = file_get_contents($this->url, false, $context);

        $bytes = strlen($archive);

        $this->notifier(STREAM_NOTIFY_PROGRESS, null, null, null, $bytes, $bytes);

        return $archive;
    }

    protected function notifier($status, $severity, $message, $code, $transferred, $max)
    {
        static $startTime;
        static $filesize = 0;

        switch($status) {
            case STREAM_NOTIFY_CONNECT:
                $startTime = microtime(true);
                break;

            case STREAM_NOTIFY_FILE_SIZE_IS:
                $filesize = $max;
                break;

            case STREAM_NOTIFY_PROGRESS:
                if ($filesize) {
                    call_user_func($this->callback,
                        $transferred,
                        $filesize,
                        microtime(true) - $startTime
                    );
                }
                break;
        }
    }
}
