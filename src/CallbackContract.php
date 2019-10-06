<?php

namespace Bhittani\Download;

interface CallbackContract
{
    /**
     * Accept a progress callback.
     *
     * @param callable $callback
     */
    public function callback(callable $callback);
}
