<?php

namespace Bhittani\Download;

class ZipTest extends TestCase
{
    /** @test */
    function it_downloads_a_zip_archive()
    {
        $zip = new Zip();

        $zip->download($this->fixture('archive.zip'), $this->temp('folder'));

        $this->assertFileExists($file = $this->temp('folder/file.txt'));

        $this->assertEquals("Example file.\n", file_get_contents($file));
    }
}
