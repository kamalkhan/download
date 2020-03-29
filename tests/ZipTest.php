<?php

namespace Bhittani\Download;

class ZipTest extends TestCase
{
    /** @test */
    function it_downloads_a_zip_archive()
    {
        $zip = new Zip($this->fixture('archive.zip'));

        $zip->download($this->temp('folder'));

        $this->assertFileExists($file = $this->temp('folder/file.txt'));

        $this->assertEquals("Example file.\n", file_get_contents($file));
    }

    /** @test */
    function it_accepts_a_progress_callback()
    {
        $data = [];

        $zip = new Zip($this->fixture('archive.zip'));

        $zip->callback(function ($bytes, $total, $time) use (& $data) {
            $data = compact('bytes', 'total', 'time');
        });

        $zip->download($destination = $this->temp('folder'));

        $bytes = filesize($destination . '/file.txt');

        $this->assertNotEmpty($bytes, $data['bytes']);
        $this->assertNotEmpty($bytes, $data['total']);
        $this->assertEquals('double', gettype($data['time']));
    }

    /** @test */
    function it_throws_a_CanNotWriteException_if_the_destination_already_exists()
    {
        mkdir($destination = $this->temp('folder'), 0777, true);

        try {
            (new Zip($this->fixture('archive.zip')))
                ->download($destination);
        } catch (CanNotWriteException $e) {
            return $this->assertEquals(
                "Destination path [{$destination}] already exists.",
                $e->getMessage()
            );
        }

        $this->fail(sprintf(
            'Expected a %s exception to be thrown.',
            CanNotWriteException::class
        ));
    }
}
