<?php

namespace Bhittani\Download;

interface Contract
{
    /**
     * Download a resource.
     *
     * @param string $resource
     * @param string $destination
     * @throws CanNotWriteException if the destination path already exists.
     */
    public function download($resource, $destination);
}
