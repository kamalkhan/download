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
            static $dots = 1;
            static $speed = 1;

            if ($speed++ > 200) {
                $speed = 1;

                if ($dots++ > 2) {
                    $dots = 1;
                }
            }

            $progress = "\e[K\rDownloading".str_repeat('.', $dots);
        } else {
            $length = $bytes / $total * 50;
            $progress = vsprintf("\e[K\r[%-50s] %d%% (%s/%s) %2ds", [
                str_repeat('=', $length).'>',
                $length * 2,
                $this->bytesForHumans($bytes),
                $this->bytesForHumans($total),
                $time,
            ]);
        }

        if ($this->printer) {
            call_user_func($this->printer, $progress);
        } else {
            fwrite(STDOUT, $progress);
        }
    }

    protected function bytesForHumans($bytes, $precision = 2)
    {
        return number_format($bytes / 1048576, $precision).' MB';
    }
}
