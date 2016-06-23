# Allay
[![Latest Version on Packagist][icon-version]][link-version]
[![Software License][icon-license]](LICENSE.md)
[![Build Status][icon-build]][link-build]
[![Coverage Status][icon-coverage]][link-coverage]
[![Code Climate][icon-climate]][link-climate]
[![Total Downloads][icon-downloads]][link-downloads]

`Allay` is a Laravel helper to easily create restful API's.
It's designed based on the idea that the resource is responsible for it's own actions.
Each of those restful actions are enabled by a boilerplate controller that works for all resources.
Every part of `Allay` is customizable and extendable, making it usable for a lot of usecases.

## Requirements
`Allay` will work with the following requirements.

- **PHP 5.6+**
- **Laravel 5.1+**

## Install

### [Composer](https://getcomposer.org/)
Composer is a nice tool to download and manage external packages within PHP.
If you still live in the dark ages, take a look at their site.

You can add `Allay` within the require section of your composer.json.

```json
{
    "require": {
        "bycedric/allay": "0.2.*"
    }
}
```

Or execute the following code inside your CLI.

```bash
$ composer require bycedric/allay
```

### [Laravel](http://laravel.com/)
After the composer installation, we need to add it to Laravel.
This can be done by adding the following code to the **/config/app.php**.

```php
'providers' => [

    /*
     * Laravel Framework Service Providers...
     */
    ...,

    /*
     * Application Service Providers...
     */
    ...,

    ByCedric\Allay\Providers\LaravelServiceProvider::class,

]
```

> Please add the service provider to the **bottom** of the providers list. If you don't, routes cannot be overwritten.

### [Lumen](http://lumen.laravel.com/)
You can also get `Allay` working on Lumen, a light-weight and blazing fast Laravel version.
This can be done by adding the following code to the **/bootstrap/app.php**.

```php
/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
...
*/

$app->register(ByCedric\Allay\Providers\LumenServiceProvider::class);
```

## Usage
To get started with `Allay` take a look at the [wiki](../../wiki) (soon available) pages.

## Extensions
`Allay` is designed to be useful in as much use cases as possible, therefore the core is unopinionated.
From the there, you can go your own way. To help you in that, here are some extensions.

- JSON API (soon available)

## Change log
Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing
If you want to extend `Allay` make sure you run the tests to validate the code.

```bash
$ composer test
```

## Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security
If you discover any security related issues, please email me@bycedric.com instead of using the issue tracker.

## Credits
- [Cedric van Putten](https://github.com/byCedric)
- [All Contributors](../../contributors)

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[icon-version]: https://img.shields.io/packagist/v/byCedric/Allay.svg?style=flat-square
[icon-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[icon-build]: https://img.shields.io/travis/byCedric/Allay/master.svg?style=flat-square
[icon-coverage]: https://img.shields.io/coveralls/byCedric/Allay/master.svg?style=flat-square
[icon-climate]: https://img.shields.io/codeclimate/github/byCedric/Allay.svg?style=flat-square
[icon-downloads]: https://img.shields.io/packagist/dt/bycedric/allay.svg?style=flat-square

[link-version]: https://packagist.org/packages/bycedric/allay
[link-build]: https://travis-ci.org/byCedric/Allay
[link-coverage]: https://coveralls.io/r/byCedric/Allay
[link-climate]: https://codeclimate.com/github/byCedric/Allay
[link-downloads]: https://packagist.org/packages/bycedric/allay
[link-ext-json-api]: https://github.com/byCedric/Allay-json-api
