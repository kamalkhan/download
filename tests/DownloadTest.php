<?php

namespace Bhittani\Download;

use DirectoryIterator;
use PHPUnit\Framework\TestCase;

class DownloadTest extends TestCase
{
    var $archive = __DIR__.'/fixtures/archive.zip';
    var $file = __DIR__.'/fixtures/destination/file.txt';

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

    /** @test */
    function it_downloads_an_archive_to_a_path()
    {
        $download = new Download($this->archive);

        $download->to(dirname($this->file));

        $this->assertFileExists($this->file);

        $this->assertEquals("Example file.\n", file_get_contents($this->file));
    }
}
