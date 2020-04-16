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

trait AcceptsCallback
{
    protected $callback;

    public function callback(callable $callback)
    {
        $this->callback = $callback;

        return $this;
    }
}
