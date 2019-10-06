<?php

namespace Bhittani\Download;

interface Contract
{
    /**
     * Download a file.
     *
     * @param string $file
     * @param string $destination
     * @throws CanNotWriteException if the destination path already exists.
     */
    public function download($file, $destination);
}
