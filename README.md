# simple-cache

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Simple implementation of the proposed
[psr-16 simplecache standard](https://github.com/php-fig/simplecache). It is
recommended that you familiarize yourself with
[the proposal](https://github.com/php-fig/fig-standards/blob/master/proposed/simplecache.md)
when using this package.

## Install

Via Composer

``` bash
$ composer require jlaswell/simple-cache
```

## Usage

Single values
``` php
$cache = new Jlaswell\SimpleCache\ArrayCache();
$cache->set('key1' 'value1');
// Do operations
$key1 = $cache->get('key1');
```

Multiple values
``` php
$cache = new Jlaswell\SimpleCache\ArrayCache();
$cache->setMultiple(['key1' => 'value1', 'key2' => 'value2']);
// Do operations
$data = $cache->getMultiple(['key1', 'key2']);
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed
recently.

## Testing

Tests for simple-cache are designed to be run against running instances of each
of the available drivers. This means that we utilize Docker to run instances of
each driver such as redis during testing. The following will start up all of the
available drivers and run all of the available tests.

``` bash
$ docker-compose up -d
$ composer test
```

simple-cache centralizes most unit tests into a single trait that is used for
each driver's unit test. Each unit test simply implements a `buildCache` method,
uses the `CacheInterfaceTestCases` trait, and tests will be run for that driver.
The [ArrayCacheTest](https://github.com/jlaswell/simple-cache/blob/master/tests/ArrayCacheTest.php)
is a good example to understand the structure and trait usage.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for
details.

## Security

If you discover any security related issues, please email
john.n.laswell+github@gmail.com instead of using the issue tracker.

## Credits

- [John Laswell](https://github.com/jlaswell)
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/jlaswell/simple-cache.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/jlaswell/simple-cache/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/jlaswell/simple-cache.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/jlaswell/simple-cache.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/jlaswell/simple-cache.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/jlaswell/simple-cache
[link-travis]: https://travis-ci.org/jlaswell/simple-cache
[link-scrutinizer]: https://scrutinizer-ci.com/g/jlaswell/simple-cache/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/jlaswell/simple-cache
[link-downloads]: https://packagist.org/packages/jlaswell/simple-cache
[link-author]: https://github.com/jlaswell
[link-contributors]: ../../contributors
