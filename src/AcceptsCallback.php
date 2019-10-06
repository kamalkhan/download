<?php

namespace Bhittani\Download;

trait AcceptsCallback
{
    protected $callback;

    public function callback(callable $callback)
    {
        $this->callback = $callback;

        return $this;
    }
}
