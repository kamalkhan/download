<?php

namespace Bhittani\Download;

class DownloadTest extends TestCase
{
    /** @test */
    function it_downloads_an_archive_to_a_path()
    {
        $download = new Download($this->archive);

        $download->to(dirname($this->file));

        $this->assertFileExists($this->file);

        $this->assertEquals("Example file.\n", file_get_contents($this->file));
    }
}
