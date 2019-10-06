<?php

namespace Bhittani\Download;

use ZipArchive;
use InvalidArgumentException;

class Zip implements Contract
{
    /**
     * @inheritDoc
     *
     * @param string $file
     * @param string $destination
     * @param null|callable|Contract $callbackOrDownloader
     */
    public function download($file, $destination, $callbackOrDownloader = null)
    {
        $destination = rtrim($destination, '\/');
        $parent = dirname($destination);
        $archive = $destination.'.zip';

        if (file_exists($destination)) {
            throw new CanNotWriteException($destination);
        }

        $downloader = new File;

        if ($callbackOrDownloader) {
            if (is_callable($callbackOrDownloader)) {
                $downloader->callback($callbackOrDownloader);
            } else {
                $downloader = $callbackOrDownloader;
            }
        }

        if (! $downloader instanceof Contract) {
            throw new InvalidArgumentException(sprintf(
                "Downloader must be a callable or an instance of %s.",
                InvalidArgumentException::class
            ));
        }

        $downloader->download($file, $archive);

        $name = $this->extract($archive, $parent);

        rename($parent.'/'.$name, $destination);
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
}
