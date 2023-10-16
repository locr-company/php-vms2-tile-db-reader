# Vms2TileDbReader

![php](https://img.shields.io/badge/php-%3E%3D%208.1-8892BF.svg)
[![codecov](https://codecov.io/gh/locr-company/php-vms2-tile-db-reader/graph/badge.svg?token=4VnTf1XVY2)](https://codecov.io/gh/locr-company/php-vms2-tile-db-reader)
![github_workflow_status](https://img.shields.io/github/actions/workflow/status/locr-company/php-vms2-tile-db-reader/php-8.1.yml)
![github_tag](https://img.shields.io/github/v/tag/locr-company/php-vms2-tile-db-reader)
![packagist](https://img.shields.io/packagist/v/locr-company/vms2-tile-db-reader)

## Installation

```bash
cd /path/to/your/php/project
composer require locr-company/vms2-tile-db-reader
```

## Basic Usage

```php
<?php

use Locr\Lib\Vms2TileDbReader\Sources\SQLite;

$tileDb = new SQLite('germany.sqlite');
$tileData = $tileDb->getRawData(x: 34686, y: 21566, z: 16, key: 'building', value: '*', type: 'Polygons');

header('Content-Type: application/octet-stream'); // The Content-Type is required for the Web-App.
print $tileData;
```

## Development

Clone the repository

```bash
git clone git@github.com:locr-company/php-vms2-tile-db-reader.git
cd php-vms2-tile-db-reader/.git/hooks && ln -s ../../git-hooks/* . && cd ../..
composer install
```