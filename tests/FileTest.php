<?php

namespace Bhittani\Download;

class FileTest extends TestCase
{
    /** @test */
    function it_downloads_a_file()
    {
        $file = new File;

        $file->download($this->fixture('file.txt'), $destination = $this->temp('foo.txt'));

        $this->assertFileExists($destination);

        $this->assertEquals("Example file.\n", file_get_contents($destination));
    }

    /** @test */
    function it_accepts_a_progress_callback()
    {
        $data = [];

        $file = new File(function ($bytes, $total, $time) use (& $data) {
            $data = compact('bytes', 'total', 'time');
        });

        $file->download($this->fixture('file.txt'), $destination = $this->temp('foo.txt'));

        $bytes = strlen(file_get_contents($destination));

        $this->assertEquals($bytes, $data['bytes']);
        $this->assertEquals($bytes, $data['total']);
        $this->assertEquals('double', gettype($data['time']));
    }

    /** @test */
    function it_throws_a_CanNotWriteException_if_the_destination_already_exists()
    {
        $this->touch($file = $this->temp('foo.txt'));

        try {
            (new File)->download($this->fixture('file.txt'), $file);
        } catch (CanNotWriteException $e) {
            return $this->assertEquals(
                "Destination path [{$file}] already exists.",
                $e->getMessage()
            );
        }

        $this->fail(sprintf(
            'Expected a %s exception to be thrown.',
            CanNotWriteException::class
        ));
    }
}
