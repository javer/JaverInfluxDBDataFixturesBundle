JaverInfluxDBDataFixturesBundle
===============================

This bundle integrates the [InfluxDB Data Fixtures library](https://github.com/javer/influxdb-data-fixtures)
into Symfony so that you can use [Alice](https://github.com/nelmio/alice) to load your fixtures into InfluxDB.

[![Build Status](https://github.com/javer/JaverInfluxDBDataFixturesBundle/actions/workflows/test.yaml/badge.svg)](https://github.com/javer/JaverInfluxDBDataFixturesBundle/actions/workflows/test.yaml)

Installation
============

Make sure Composer is installed globally, as explained in the
[installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Applications that use Symfony Flex
----------------------------------

Open a command console, enter your project directory and execute:

```console
$ composer require javer/influxdb-data-fixtures-bundle
```

Applications that don't use Symfony Flex
----------------------------------------

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require javer/influxdb-data-fixtures-bundle
```

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
// config/bundles.php

return [
    // ...
    Javer\InfluxDB\DataFixturesBundle\JaverInfluxDBDataFixturesBundle::class => ['dev' => true, 'test' => true],
];
```

Usage
=====

Refer to the [AliceDataFixtures](https://github.com/theofidry/AliceDataFixtures) documentation
about creating of data fixtures for the Doctrine ODM. 

After creating you can load fixtures using a `LoaderInterface`:
```php
<?php

$files = [
    'path/to/tests/DataFixtures/InfluxDB/measurement1.yaml',
    'path/to/tests/DataFixtures/InfluxDB/measurement2.yaml',
];

$loader = $container->get('javer_influxdb_data_fixtures.loader.influxdb');

// Purge the objects, create PHP objects from the fixture files and persist them
$objects = $loader->load($files);

// $objects is now an array of persisted `Measurement1` and `Measurement2`
``` 
