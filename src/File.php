<?php

namespace Bhittani\Download;

class File extends Download
{
    protected $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    /** @inheritDoc */
    public function download($destination, array $options = [])
    {
        if (file_exists($destination)) {
            throw new CanNotWriteException($destination);
        }

        if (! is_dir($dir = dirname($destination))) {
            mkdir($dir, 0777, true);
        }

        file_put_contents($destination, $this->getContents($options));
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

        $contents = file_get_contents($this->file, false, $context);

        $bytes = strlen($contents);

        $this->notifier(STREAM_NOTIFY_PROGRESS, null, null, null, $bytes, $bytes);

        return $contents;
    }

    protected function notifier($status, $severity, $message, $code, $transferred, $max)
    {
        static $filesize, $startTime;

        $filesize = $filesize ?: $max ?: 0;
        $startTime = $startTime ?: microtime(true);

        switch ($status) {
            case STREAM_NOTIFY_CONNECT:
                $startTime = microtime(true);
                break;

            case STREAM_NOTIFY_FILE_SIZE_IS:
                $filesize = $max;
                break;

            case STREAM_NOTIFY_PROGRESS:
                if ($filesize && $this->callback) {
                    call_user_func(
                        $this->callback,
                        $transferred,
                        $filesize,
                        microtime(true) - $startTime
                    );
                }
                break;
        }
    }
}
