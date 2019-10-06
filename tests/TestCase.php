<?php

namespace Bhittani\Download;

use DirectoryIterator;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

abstract class TestCase extends PHPUnitTestCase
{
    var $temp = __DIR__.'/temp';
    var $fixtures = __DIR__.'/fixtures';

    function tearDown()
    {
        $this->remove($this->temp);
    }

    function touch($file)
    {
        if (! is_dir($dir = dirname($file))) {
            mkdir($dir, 0777, true);
        }

        touch($file);
    }

    function remove($directory)
    {
        if (is_dir($directory)) {
            foreach (new DirectoryIterator($directory) as $file) {
                if (! $file->isDot()) {
                    unlink($file->getPathname());
                }
            }

            rmdir($directory);
        }
    }

    function fixture($path)
    {
        return rtrim($this->fixtures.'/'.ltrim($path, '\/'), '\/');
    }

    function temp($path)
    {
        return rtrim($this->temp.'/'.ltrim($path, '\/'), '\/');
    }
}
