# Authsum

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]][link-license]
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Yet another PHP authentication library.

## Installation

Install `Authsum` via [Composer](https://getcomposer.org/):

``` bash
$ composer require rougin/authsum
```

## Basic Usage

``` php
use Rougin\Authsum\ArrayChecker;
use Rougin\Authsum\Authentication;

$users = array();

$users[] = array('username' => 'rougin', 'password' => 'rougin');
$users[] = array('username' => 'roycee', 'password' => 'roycee');
$users[] = array('username' => 'gutibb', 'password' => 'gutibb');
$users[] = array('username' => 'testtt', 'password' => 'testtt');

$checker = new ArrayChecker($users); // Used for checking the data.

$checker->hashed(false); // Disables checking of hashed password.

$credentials = array('username' => 'rougin', 'password' => 'rougin');

// It validates the credentials first in validate() method.
// Then it returns the success() method if authenticated properly.
// If it fails after checking, then it returns the error() method.
// Class "Authentication" can also be extended, see below.
(new Authentication)->authenticate($checker, $credentials);
```

### Extendable methods

``` php
class Authentication extends \Rougin\Authsum\Authentication
{
    protected function success($match)
    {
        // Setting session variables or current user, etc.
    }

    protected function error($type = self::NOT_FOUND)
    {
        // A HTTP 302 redirection, throw exception, etc.
        // If the validation fails, it will go here with a $type of "INVALID"
    }

    protected function validate(array $credentials)
    {
        // CSRF, token checking, etc.
    }
}
```

### Available Checkers

* [ArrayChecker](src/Checker/ArrayChecker.php) - checks defined data in an array.
* [DoctrineChecker](src/Checker/DoctrineChecker.php) - requires `doctrine/orm` to be installed
* [EloquentChecker](src/Checker/EloquentChecker.php) - requires `illuminate/database` to be installed
* [PdoChecker](src/Checker/PdoChecker.php) - uses the `PDO` library

You can also create a new checker by implementing it in [CheckerInterface](src/Checker/CheckerInterface.php).

## Changelog

Please see [CHANGELOG][link-changelog] for more information what has changed recently.

## Testing

``` bash
$ composer require doctrine/orm illuminate/database --dev
$ composer test
```

## Credits

- [All contributors][link-contributors]

## License

The MIT License (MIT). Please see [LICENSE][link-license] for more information.

[ico-code-quality]: https://img.shields.io/scrutinizer/g/rougin/authsum.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/rougin/authsum.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/rougin/authsum.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/rougin/authsum/master.svg?style=flat-square
[ico-version]: https://img.shields.io/packagist/v/rougin/authsum.svg?style=flat-square

[link-changelog]: https://github.com/rougin/authsum/blob/master/CHANGELOG.md
[link-code-quality]: https://scrutinizer-ci.com/g/rougin/authsum
[link-contributors]: https://github.com/rougin/authsum/contributors
[link-downloads]: https://packagist.org/packages/rougin/authsum
[link-license]: https://github.com/rougin/authsum/blob/master/LICENSE.md
[link-packagist]: https://packagist.org/packages/rougin/authsum
[link-scrutinizer]: https://scrutinizer-ci.com/g/rougin/authsum/code-structure
[link-travis]: https://travis-ci.org/rougin/authsum