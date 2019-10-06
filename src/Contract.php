<?php

namespace Bhittani\Download;

interface Contract
{
    /**
     * Download to the provided destination path.
     *
     * @param string $destination
     * @throws CanNotWriteException if the destination path already exists.
     */
    public function download($destination);
}
