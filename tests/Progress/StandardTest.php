<?php

namespace Bhittani\Download\Progress;

use Bhittani\Download\GitHub;
use Bhittani\Download\TestCase;

class StandardTest extends TestCase
{
    var $repository = 'kamalkhan/example';

    /** @test */
    function it_accepts_a_progress_callback()
    {
        $output = '';

        $github = new GitHub($this->repository);

        $github->callback(new Standard(function ($progress) use (&$output) {
            $output = trim($progress);
        }));

        $github->download($this->temp('folder'));

        $this->assertNotEmpty($output);
        // $this->assertEquals("\n[==================================================>] 100% (376 B/376 B)", preg_replace('/ \d+s$/', '', $output));
    }
}
