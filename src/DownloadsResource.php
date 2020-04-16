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

trait DownloadsResource
{
    protected function downloadResource(Contract $downloader, $destination, $options = [], $callback = null)
    {
        if (! $callback
            && $this instanceof CallbackContract
            && property_exists($this, 'callback')
        ) {
            $callback = $this->callback;
        }

        if ($callback && $downloader instanceof CallbackContract) {
            $downloader->callback($callback);
        }

        if ($options) {
            $downloader->download($destination, $options);
        } else {
            $downloader->download($destination);
        }
    }
}
