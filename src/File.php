<?php

/*
 * This file is part of bhittani/download.
 *
 * (c) Kamal Khan <shout@bhittani.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Bhittani\Download;

class File extends Download
{
    protected $file;
    protected $filesize;
    protected $startTime;

    public function __construct($file)
    {
        $this->file = $file;
    }

    /** {@inheritdoc} */
    public function download($destination, array $options = [])
    {
        $this->filesize = null;
        $this->startTime = null;

        if (file_exists($destination)) {
            throw new CanNotWriteException($destination);
        }

        if (! is_dir($dir = dirname($destination))) {
            mkdir($dir, 0777, true);
        }

        file_put_contents($destination, $this->getContents($options));
    }

    public function notifier($status, $severity, $message, $code, $transferred, $max)
    {
        $this->filesize = $this->filesize ?: $max ?: 0;
        $this->startTime = $this->startTime ?: microtime(true);

        switch ($status) {
            case STREAM_NOTIFY_CONNECT:
                $this->startTime = microtime(true);
                break;
            case STREAM_NOTIFY_FILE_SIZE_IS:
                $this->filesize = $max;
                break;
            case STREAM_NOTIFY_PROGRESS:
                if ($this->callback && ($transferred + 8192) > 0) {
                    $transferred += 8192;
                    if ($transferred >= $this->filesize) {
                        $transferred = $this->filesize;
                    }
                    call_user_func(
                        $this->callback,
                        $transferred,
                        $this->filesize,
                        microtime(true) - $this->startTime
                    );
                }
                break;
        }
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
}
