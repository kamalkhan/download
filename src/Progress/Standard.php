<?php

/*
 * This file is part of bhittani/download.
 *
 * (c) Kamal Khan <shout@bhittani.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Bhittani\Download\Progress;

class Standard
{
    /** @var callable */
    protected $printer;

    public function __construct(callable $printer = null)
    {
        $this->printer = $printer;
    }

    public function __invoke($bytes, $total, $time)
    {
        $progress = '';

        if (! $total) {
            $progress = sprintf("\e[K\rUnknown filesize.. %s done.", $this->bytesForHumans($bytes));
        } else {
            $length = $bytes / $total * 50;
            $progress = sprintf("\e[K\r[%-50s] %d%% (%s/%s) %2ds", str_repeat('=', $length).'>', $length * 2, $this->bytesForHumans($bytes), $this->bytesForHumans($total), $time);
        }

        if ($this->printer) {
            call_user_func($this->printer, $progress);
        } else {
            fwrite(STDOUT, $progress);
        }
    }

    protected function bytesForHumans($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision).' '.$units[$pow];
    }
}
