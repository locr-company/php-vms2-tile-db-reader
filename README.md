# Vms2TileDbReader

![php](https://img.shields.io/badge/php-%3E%3D%208.1-8892BF.svg)
[![github_workflow_status](https://img.shields.io/github/actions/workflow/status/locr-company/php-vms2-tile-db-reader/php-8.1.yml)](https://github.com/locr-company/php-vms2-tile-db-reader/actions/workflows/php.yml)
[![codecov](https://codecov.io/gh/locr-company/php-vms2-tile-db-reader/graph/badge.svg?token=920M72RYI9)](https://codecov.io/gh/locr-company/php-vms2-tile-db-reader)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=locr-company_php-vms2-tile-db-reader&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=locr-company_php-vms2-tile-db-reader)
[![github_tag](https://img.shields.io/github/v/tag/locr-company/php-vms2-tile-db-reader)](https://github.com/locr-company/php-vms2-tile-db-reader/tags)
[![packagist](https://img.shields.io/packagist/v/locr-company/vms2-tile-db-reader)](https://packagist.org/packages/locr-company/vms2-tile-db-reader)

## Installation

```bash
cd /path/to/your/php/project
composer require locr-company/vms2-tile-db-reader
```

## Basic Usage

```php
<?php

use Locr\Lib\Vms2TileDbReader\DataType;
use Locr\Lib\Vms2TileDbReader\Sources\SQLite;

$tileDb = new SQLite('germany.sqlite');
$tileData = $tileDb->getRawData(x: 34686, y: 21566, z: 16, key: 'building', value: '*', type: DataType::Polygons);

header('Content-Type: application/octet-stream'); // The Content-Type is required for the Web-App.
print $tileData;
```

## Development

Clone the repository

```bash
git clone git@github.com:locr-company/php-vms2-tile-db-reader.git
cd php-vms2-tile-db-reader && composer install
```