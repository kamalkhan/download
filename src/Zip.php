<?php

namespace Bhittani\Download;

use ZipArchive;

class Zip extends File
{
    /** @inheritDoc */
    public function download($resource, $destination, array $options = [])
    {
        $destination = rtrim($destination, '\/');
        $parent = dirname($destination);
        $archive = $destination.'.zip';

        parent::download($resource, $archive);

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
