<?php

namespace Bhittani\Download;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
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
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $directory,
                RecursiveDirectoryIterator::SKIP_DOTS
            ),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $file) {
            $path = $file->getRealPath();

            if ($file->isDir()) {
                rmdir($path);
            } else {
                unlink($path);
            }
        }

        rmdir($directory);
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
