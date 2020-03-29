<?php

namespace Bhittani\Download;

use Exception;
use RuntimeException;

class GitHub extends Download
{
    use DownloadsResource;

    protected $repository;

    protected $version = 'latest';

    protected $api = 'https://api.github.com';

    protected $downloadUrl = 'https://codeload.github.com/%s/legacy.zip/%s';

    public function __construct($repository, $version = 'latest')
    {
        $this->version($version);

        $this->repository = $repository;
    }

    public function version($version)
    {
        $this->version = $version;

        return $this;
    }

    /** @inheritDoc */
    public function download($destination, array $options = [])
    {
        $version = strtolower($this->version ?: 'latest');

        if ($version == 'latest') {
            $version = $this->getVersion();
        }

        $zip = sprintf($this->downloadUrl, $this->repository, $version);

        $this->downloadResource(new Zip($zip), $destination, $options);

        return $version;
    }

    public function getVersion()
    {
        try {
            return $this->getLatestVersion($this->repository);
        } catch (Exception $e) {
            return 'master';
        }
    }

    protected function getLatestVersion($repository)
    {
        return $this->getLatestTag($repository)->name;
    }

    protected function getLatestTag($repository)
    {
        $tags = $this->getTags($repository);

        if (is_array($tags) && $tags) {
            return array_shift($tags);
        }

        throw new RuntimeException("No tag available for [{$repository}].");
    }

    protected function getTags($repository)
    {
        return $this->fetch("/repos/{$repository}/tags");
    }

    protected function fetch($uri)
    {
        $ch = curl_init(rtrim($this->api.'/'.ltrim($uri, '\/'), '\/'));

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/vnd.github.v3+json',
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $response = curl_exec($ch);

        try {
            if ($error = curl_error($ch)) {
                throw new RuntimeException($error);
            }
        } finally {
            curl_close($ch);
        }

        return json_decode($response);
    }
}
