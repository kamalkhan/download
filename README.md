# Download [![Build Status][icon-status]][link-status] [![Total Downloads][icon-downloads]][link-downloads] [![MIT License][icon-license]](LICENSE.md)

Programmatically download files, zip archives and GitHub repositories.

- [Requirements](#requirements)
- [Install](#install)
- [Usage](#usage)
  - [Downloading a file](#downloading-a-file)
  - [Downloading/extracting a zip archive](#downloadingextracting-a-zip-archive)
  - [Downloading a GitHub repository](#downloading-a-github-repository)
  - [Using a progress callback handler](#using-a-progress-callback-handler)
- [Changelog](#changelog)
- [Testing](#testing)
- [Contributing](#contributing)
- [Security](#security)
- [Credits](#credits)
- [License](#license)

## Requirements

- PHP >= 5.6
- PHP Zip Extension

## Install

You may install this package using [composer][link-composer].

```shell
$ composer require bhittani/download --prefer-dist
```

## Usage

This package currently allows downloading/extraction of files (local/network), zip files (local/network) and [GitHub][link-github] repositories.

### Downloading a file

The file downloader allows downloading of a single file either from a local disk or over a network.

```php
<?php
use Bhittani\Download\File;

// Create an instance of Bhittani\Download\File with the path to the source file.
$file = new File('path/to/a/local/or/www/file.ext');

// Download the file to a local destination.
$file->download('path/to/local/destination/file.ext');
```

### Downloading/extracting a zip archive

The zip downloader allows downloading of a zip file either from a local disk or over a network and extracting its contents into a local folder.

```php
<?php
use Bhittani\Download\Zip;

// Create an instance of Bhittani\Download\Zip with path to the source zip archive.
$zip = new Zip('path/to/a/local/or/www/archive.zip');

// Download and extract the zip archive to a local destination.
$zip->download('path/to/local/destination/folder');
```

### Downloading a GitHub repository

The GitHub downloader allows downloading of a GitHub repository into a local folder.

```php
<?php
use Bhittani\Download\GitHub;

// Create an instance of Bhittani\Download\GitHub with the name of respository.
$gitHub = new GitHub('org/repo');

// Download the GitHub repository to a local destination.
$gitHub->download('path/to/local/destination/folder');
```

### Using a progress callback handler

The download instances also accept a progress callback.

```php
$downloader = new \Bhittani\Download\GitHub('org/repo');

$downloader->callback(function ($transferred, $total, $time) {
    // $transferred will contain the number of bytes transferred.
    // $total will contain the total bytes.
    // $time will contain the time consumed in milliseconds.
});

$downloader->download('path/to/destionation/folder');
```

> An stdout progress callback is available as an invocable class `Bhittani\Download\Progress\Standard` which may be useful for console output.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed.

## Testing

```shell
git clone https://github.com/kamalkhan/download

cd download

composer install

composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email `shout@bhittani.com` instead of using the issue tracker.

## Credits

- [Kamal Khan](http://bhittani.com)
- [All Contributors](https://github.com/kamalkhan/download/contributors)

## License

The MIT License (MIT). Please see the [License File](LICENSE.md) for more information.

<!--Status-->
[icon-status]: https://img.shields.io/github/workflow/status/kamalkhan/download/main?style=flat-square
[link-status]: https://github.com/kamalkhan/download

<!--Downloads-->
[icon-downloads]: https://img.shields.io/packagist/dt/bhittani/download.svg?style=flat-square
[link-downloads]: https://packagist.org/packages/bhittani/download

<!--License-->
[icon-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square

<!--composer-->
[link-composer]: https://getcomposer.org

<!--github-->
[link-github]: https://github.com
