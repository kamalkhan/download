<?php

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
