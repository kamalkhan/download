<?php

namespace Bhittani\Download;

use DirectoryIterator;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

abstract class TestCase extends PHPUnitTestCase
{
    var $archive = __DIR__.'/fixtures/archive.zip';
    var $file = __DIR__.'/fixtures/temp/file.txt';

    function tearDown()
    {
        if (is_dir($dir = dirname($this->file))) {
            foreach (new DirectoryIterator($dir) as $file) {
                if (! $file->isDot()) {
                    unlink($file->getPathname());
                }
            }

            rmdir($dir);
        }
    }
}
