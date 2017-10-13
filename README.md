# Authsum

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

A very simple authentication library for PHP.

## Install

Via Composer

``` bash
$ composer require rougin/authsum
```

## Usage

``` php
$users = [
    [ 'username' => 'rougin', 'password' => 'rougin' ],
    [ 'username' => 'roycee', 'password' => 'roycee' ],
    [ 'username' => 'gutibb', 'password' => 'gutibb' ],
    [ 'username' => 'testtt', 'password' => 'testtt' ],
];

$checker = new Rougin\Authsum\ArrayChecker($users); // Used for checking the data.

$checker->hashed(false); // Disables checking of hashed password.

$authentication = new Rougin\Authsum\Authentication;

$credentials = [ 'username' => 'rougin', 'password' => 'rougin' ];

// It validates the credentials first in validate() method.
// Then it returns the success() method if authenticated properly.
// If it fails after checking, then it returns the error() method.
// Class "Authentication" can also be extended, see below.
$authentication->authenticate($checker, $credentials);
```

### Extendable methods

``` php
class Authentication extends \Rougin\Authsum\Authentication
{
    protected function success($match)
    {
        // Do something like setting session/user, etc. ...
    }

    protected function error($type = self::NOT_FOUND)
    {
        // Do something like a redirect, throw exception, etc. ...
        // If the validation fails, it will go here with a $type of "INVALID"
    }

    protected function validate(array $credentials)
    {
        // Do something like CSRF, token checking, etc. ...
    }
}
```

### Available Checkers

* [ArrayChecker](src/Checker/ArrayChecker.php) - checks defined data in an array.
* [DoctrineChecker](src/Checker/DoctrineChecker.php) - requires `doctrine/orm` to be installed
* [EloquentChecker](src/Checker/EloquentChecker.php) - requires `illuminate/database` to be installed
* [PdoChecker](src/Checker/PdoChecker.php) - uses the `PDO` library

You can also create a new checker by implementing it in [CheckerInterface](src/Checker/CheckerInterface.php).

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer require doctrine/orm illuminate/database --dev
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email rougingutib@gmail.com instead of using the issue tracker.

## Credits

- [Rougin Royce Gutib][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/rougin/authsum.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/rougin/authsum/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/rougin/authsum.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/rougin/authsum.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/rougin/authsum.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/rougin/authsum
[link-travis]: https://travis-ci.org/rougin/authsum
[link-scrutinizer]: https://scrutinizer-ci.com/g/rougin/authsum/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/rougin/authsum
[link-downloads]: https://packagist.org/packages/rougin/authsum
[link-author]: https://github.com/rougin
[link-contributors]: ../../contributors
