<?php

namespace Bhittani\Download;

use InvalidArgumentException;

class CanNotWriteException extends InvalidArgumentException
{
    public function __construct($path)
    {
        parent::__construct("Destination path [{$path}] already exists.");
    }
}
