<?php

namespace Bhittani\Download;

interface Contract
{
    /**
     * Download the resource.
     *
     * @param string $destination
     *
     * @throws CanNotWriteException if the destination path already exists.
     */
    public function download($destination);
}
