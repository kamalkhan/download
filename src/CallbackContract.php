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

interface CallbackContract
{
    /**
     * Accept a progress callback.
     */
    public function callback(callable $callback);
}
