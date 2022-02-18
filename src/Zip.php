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

use ZipArchive;

class Zip extends Download
{
    use DownloadsResource;

    protected $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    /** {@inheritdoc} */
    public function download($destination, array $options = [])
    {
        $destination = rtrim($destination, '\/');
        $parent = dirname($destination);
        $archive = $destination.'.zip';

        if (file_exists($destination)) {
            throw new CanNotWriteException($destination);
        }

        $this->downloadResource(new File($this->file), $archive, $options);

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

        $parts = explode('/', str_replace('\\', '/', $binary));
        $name = array_shift($parts);

        return $name;
    }
}
