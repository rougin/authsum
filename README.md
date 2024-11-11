# Authsum

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]][link-license]
[![Build Status][ico-build]][link-build]
[![Coverage Status][ico-coverage]][link-coverage]
[![Total Downloads][ico-downloads]][link-downloads]

`Authsum` is a simple authentication package written in PHP which allows to create simple and extensible authentication logic.

## Installation

Install the `Authsum` package via [Composer](https://getcomposer.org/):

``` bash
$ composer require rougin/authsum
```

## Basic usage

Prior in using `Authsum`, a data source must be defined first (e.g, `PdoSource`):

``` php
// index.php

use Rougin\Authsum\Source\PdoSource;

// ...

// Create a PDO instance... --------------
$dsn = 'mysql:host=localhost;dbname=demo';

$pdo = new PDO($dsn, 'root', /** ... */);
// ---------------------------------------

// ...then pass it to the PdoSource ---
$source = new PdoSource($pdo);
// ------------------------------------

// ...
```

Once the source is defined, use the `Authsum` class to perform the validation logic:

``` php
// index.php

use Rougin\Authsum\Authsum;

// ...

$auth = new Authsum($source);

if ($auth->isValid($_POST))
{
    $user = $auth->getUser();

    echo 'Welcome ' . $user->getName() . '!';
}
else
{
    echo 'Invalid credentials!';
}
```

## Customization

`Authsum` also provides simple extensibility utilities to fit in from various use-cases.

### Changing fields to check

By default, `Authsum` checks the `email` as its username and `password` for the password from the payload (e.g., `$_POST`). If this is not the case, kindly update the specified fields using `setUsernameField` or `setPasswordField`:

``` php
// index.php

// ...

$auth->setUsernameField('username');
$auth->setPasswordField('password');

// ...
```

### Using sources

Sources in `Authsum` are PHP classes that provide user data. They are also used for checking the specified username and password fields against its data source:

``` php
// index.php

use Rougin\Authsum\Source\SimpleSource;

// ...

$users = array();

$users[] = array('username' => 'rougin', 'password' => 'rougin');
$users[] = array('username' => 'roycee', 'password' => 'roycee');
$users[] = array('username' => 'gutibb', 'password' => 'gutibb');
$users[] = array('username' => 'testtt', 'password' => 'testtt');

$source = new SimpleSource($users);

// ...
```

Although the usage of the `PdoSource` class is introduced from the `Basic Usage` section, the `setTableName` method can also be used to specify its database table name:

``` php
// index.php

use Rougin\Authsum\Source\PdoSource;

// ...

$source = new PdoSource($pdo);

$source->setTableName('users');

// ...
```

> [!NOTE]
> If the `setTableName` is not specified, it always refer to the `users` table.

There may be a scenario that there are other fields to use besides `username` and `password`. With this, kindly use the `setData` method:

``` php
// index.php

use Rougin\Authsum\Source\PdoSource;

// ...

$source = new PdoSource($pdo);

$data = array('type' => 'admin');

$source->setData($data);

// ...
```

If `setData` is defined, the provided data will be added as `WHERE` queries to the SQL query:

**Before**

``` sql
SELECT u.* FROM users u WHERE u.username = ?
```

**After**

``` sql
SELECT u.* FROM users u WHERE u.username = ? AND u.type = ?
```

### Creating custom sources

To create a custom source, kindly use the `SourceInterface` for its implementation:

``` php
namespace Rougin\Authsum\Source;

interface SourceInterface
{
    /**
     * Checks if exists from the source.
     *
     * @param string $username
     * @param string $password
     *
     * @return boolean
     */
    public function exists($username, $password);
}
```

## Changelog

Please see [CHANGELOG][link-changelog] for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Credits

- [All contributors][link-contributors]

## License

The MIT License (MIT). Please see [LICENSE][link-license] for more information.

[ico-build]: https://img.shields.io/github/actions/workflow/status/rougin/authsum/build.yml?style=flat-square
[ico-coverage]: https://img.shields.io/codecov/c/github/rougin/authsum?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/rougin/authsum.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-version]: https://img.shields.io/packagist/v/rougin/authsum.svg?style=flat-square

[link-build]: https://github.com/rougin/authsum/actions
[link-changelog]: https://github.com/rougin/authsum/blob/master/CHANGELOG.md
[link-contributors]: https://github.com/rougin/authsum/contributors
[link-coverage]: https://app.codecov.io/gh/rougin/authsum
[link-downloads]: https://packagist.org/packages/rougin/authsum
[link-license]: https://github.com/rougin/authsum/blob/master/LICENSE.md
[link-packagist]: https://packagist.org/packages/rougin/authsum
[link-upgrading]: https://github.com/rougin/authsum/blob/master/UPGRADING.md