<?php

namespace Bhittani\Download;

class GitHubTest extends TestCase
{
    var $repository = 'kamalkhan/example';

    /** @test */
    function it_downloads_a_github_repository()
    {
        $github = new GitHub($this->repository);

        $github->download($this->temp('folder'));

        $this->assertFileExists($file = $this->temp('folder/EXAMPLE'));

        $this->assertEquals("example\n", file_get_contents($file));
    }

    /** @test */
    function it_accepts_a_progress_callback()
    {
        $data = [];

        $github = new GitHub($this->repository);

        $github->callback(function ($bytes, $total, $time) use (& $data) {
            $data = compact('bytes', 'total', 'time');
        });

        $github->download($destination = $this->temp('folder'));

        $bytes = filesize($destination . '/EXAMPLE');

        $this->assertNotEmpty($bytes, $data['bytes']);
        $this->assertNotEmpty($bytes, $data['total']);
        $this->assertEquals('double', gettype($data['time']));
    }

    /** @test */
    function it_throws_a_CanNotWriteException_if_the_destination_already_exists()
    {
        mkdir($destination = $this->temp('folder'), 0777, true);

        try {
            (new GitHub($this->repository))
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
