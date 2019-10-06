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

    /** @test */
    function it_accepts_a_progress_callback()
    {
        $data = [];

        $callback = function ($bytes, $total, $time) use (& $data) {
            $data = compact('bytes', 'total', 'time');
        };

        $download = new Download($this->archive, $callback);

        $download->to(dirname($this->file));

        $bytes = strlen(file_get_contents($this->archive));

        $this->assertEquals($bytes, $data['bytes']);
        $this->assertEquals($bytes, $data['total']);
        $this->assertEquals('double', gettype($data['time']));
    }
}
