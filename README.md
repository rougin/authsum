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

Prior in using `Authsum`, a data source must be defined first (e.g., `BasicSource`):

``` php
// index.php

use Rougin\Authsum\Source\BasicSource;

// ...

$username = 'admin';
$password = /** ... */;

// Check if the provided username and password data ---
// matched from the given payload (e.g., $_POST) ------
$source = new BasicSource($username, $password);
// ----------------------------------------------------

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
    /** @var \Acme\Models\User */
    $user = $auth->getResult()->getField('user');

    echo 'Welcome ' . $user->getName() . '!';
}
else
{
    echo 'Invalid credentials!';
}
```

## Customization

`Authsum` also provides simple extensibility utilities to be able to fit in from various use-cases.

### Pass or fail from `Authsum`

The `Authsum` class can also be extended to provide methods if the validation logic passed or failed:

``` php
namespace Acme;

use Acme\Depots\AuditDepot;
use Acme\Errors\NoAccount;
use Rougin\Authsum\Authsum;
use Rougin\Authsum\Error;
use Rougin\Authsum\Result;

class TestAuth extends Authsum
{
    protected $audit;

    public function __construct(AuditDepot $audit)
    {
        $this->audit = $audit;
    }

    /**
     * Executes if the validation failed.
     *
     * @param \Rougin\Authsum\Error $error
     *
     * @return void
     */
    protected function failed(Error $error)
    {
        throw new NoAccount($error->getText());
    }

    /**
     * Executes if the validation passed.
     *
     * @param \Rougin\Authsum\Result $data
     *
     * @return void
     */
    protected function passed(Result $data)
    {
        /** @var string */
        $user = $data->getField('name');

        $this->audit->userLoggedIn($user);
    }
}
```

Alternatively, the `Authsum` class can also get the error or the result after validation using `getError()` and `getResult()` respectively:

``` php
// index.php

use Rougin\Authsum\Authsum;

// ...

$auth = new Authsum($source);

if ($auth->isValid($_POST))
{
    $result = $auth->getResult();

    /** @var string */
    $name = $result->getField('name');

    echo 'Welcome ' . $name . '!';
}
else
{
    $error = $auth->getError();

    echo 'Error: ' . $auth->getText();
}
```

> [!NOTE]
> An `UnexpectedValueException` will be thrown if trying to access an empty output (e.g., trying to access `getResult()` after the failed validation).

### Changing fields to check

By default, the `Authsum` class can check the `email` as its username and `password` for the password from the payload (e.g., `$_POST`). If this is not the case, kindly update the specified fields using `setUsernameField` or `setPasswordField`:

``` php
// index.php

// ...

$auth->setUsernameField('username');
$auth->setPasswordField('password');

// ...
```

> [!NOTE]
> The specified fields will be used by the `Authsum` class if they are required by the specified source (e.g., `BasicSource`, `PdoSource`).

### Using sources

Sources in `Authsum` are PHP classes that provide user data. They can be used for checking the specified username and password fields against its data source:

``` php
// index.php

use Rougin\Authsum\Authsum;
use Rougin\Authsum\Source\BasicSource;

// ...

// Initialize the source... --------------------
$username = 'admin';
$password = /** ... */;

$source = new BasicSource($username, $password);
// ---------------------------------------------

// ...then pass it to Authsum ---
$auth = new Authsum($source);
// ------------------------------

// The source will be used to check if ---
// the provided payload matches in the ---
// given payload ($_POST) from its source
$valid = $auth->isValid($_POST);
// ---------------------------------------

// ...
```

#### `PdoSource`

Besides from `BasicSource`, another available source that can be used is `PdoSource` which uses [PDO](https://www.php.net/manual/en/intro.pdo.php) to interact with a database:

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

The `setTableName` method can also be used to specify its database table name:

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

When using `PdoSource`, the value in the `password` field will be assumed as a hash (e.g., `$2y$10...`). If this is not the case, kindly add the `withoutHash` method:

``` php
// index.php

use Rougin\Authsum\Source\PdoSource;

// ...

$source = new PdoSource($pdo);

$source->withoutHash();

// ...
```

Doing this will make a strict comparison of the provided `password` against the result from the database.

#### `JwtSource`

The `JwtSource` class is a special class that checks a user's authentication using [JSON Web Token](https://en.wikipedia.org/wiki/JSON_Web_Token):

``` php
// index.php

use Rougin\Authsum\Source\JwtSource;

// ...

/** @var \Rougin\Authsum\Source\JwtParserInterface */
$parser = /** ... */;

$source = new JwtSource($parser);
```

From the example above, initializing `JwtSource` requires a `JwtParserInterface` for parsing the JSON web tokens from payload:

``` php
namespace Rougin\Authsum\Source;

interface JwtParserInterface
{
    /**
     * Parses the token string.
     *
     * @param string $token
     *
     * @return array<string, mixed>
     */
    public function parse($token);
}
```

If `JwtSource` is used as a source, the `token` field must be updated also from the `Authsum` class based on the query parameter or parsed body where the token exists:

``` php
// index.php

use Rougin\Authsum\Authsum;
use Rougin\Authsum\Source\JwtSource;

// ...

$source = new JwtSource($parser);

// Search "token" property from the payload ---
$source->setTokenField('token');
// --------------------------------------------

$auth = new Authsum($source);
```

> [!NOTE]
> If `setTokenField` is not specified, its default value is `token`.

Then use the `setUsernameField` to specify the field to be compared against the parsed data from the JSON web token:

``` php
// index.php

use Rougin\Authsum\Authsum;

// ...

$auth = new Authsum($source);

// ...

$auth->setUsernameField('email');

// The $_POST data should contains the ---
// "token" field and the "email" field ---
$valid = $auth->isValid($_POST);
// ---------------------------------------
```

### Creating custom sources

To create a custom source, kindly use the `SourceInterface` for its implementation:

``` php
namespace Rougin\Authsum\Source;

interface SourceInterface
{
    /**
     * Returns the error after validation.
     *
     * @return \Rougin\Authsum\Error
     */
    public function getError();

    /**
     * Returns the result after validation.
     *
     * @return \Rougin\Authsum\Result
     */
    public function getResult();

    /**
     * Checks if it exists from the source.
     *
     * @return boolean
     */
    public function isValid();
}
```

If the custom source requires an `username` field, kindly add the `WithUsername` interface:

``` php
namespace Rougin\Authsum\Source;

interface WithUsername
{
    /**
     * Sets the username field.
     *
     * @param string $username
     *
     * @return self
     */
    public function setUsernameField($username);

    /**
     * Sets the username.
     *
     * @param string $username
     *
     * @return self
     */
    public function setUsernameValue($username);
}
```

The `WithPassword` interface can be also added if the custom source requires a password to be defined:

``` php
namespace Rougin\Authsum\Source;

interface WithPassword
{
    /**
     * Sets the password field.
     *
     * @param string $password
     *
     * @return self
     */
    public function setPasswordField($password);

    /**
     * Sets the password value.
     *
     * @param string $password
     *
     * @return self
     */
    public function setPasswordValue($password);
}
```

Some custom sources may require to use the provided payload instead of `username` and `password` fields (e.g., `JwtSource`). With this, kindly use the `WithPayload` interface:

``` php
namespace Rougin\Authsum\Source;

interface WithPayload
{
    /**
     * Sets the prepared payload.
     *
     * @param array<string, string> $payload
     *
     * @return self
     */
    public function setPayload($payload);
}
```

## Changelog

Please see [CHANGELOG][link-changelog] for more information what has changed recently.

## Testing

If there is a need to check the source code of `Authsum` for development purposes (e.g., creating fixes, new features, etc.), kindly clone this repository first to a local machine:

``` bash
$ https://github.com/rougin/authsum.git "Sample"
```

After cloning, use `Composer` to install its required packages:

``` bash
$ cd Sample
$ composer update
```

Once the packages were installed, kindly check the following below on how to maintain the code quality and styling guide when interacting the source code of `Authsum`:

### Unit tests

`Authsum` also contains unit tests that were written in [PHPUnit](https://phpunit.de/index.html):

``` bash
$ composer test
```

When creating fixes or implementing new features, it is recommended to run the above command to always check if the updated code introduces errors during development.

### Code quality

To retain the code quality of `Authsum`, a static code analysis code tool named [PHPStan](https://phpstan.org/) is being used during development. To start, kindly install the specified package in global environment of `Composer`:

``` bash
$ composer global require phpstan/phpstan
```

Once installed, `PHPStan` can now be run using the `phpstan` command:

``` bash
$ cd Sample
$ phpstan
```

### Coding style

Asides from code quality, `Authsum` also uses a tool named [PHP Coding Standards Fixer](https://cs.symfony.com/) for maintaining an opinionated style guide. The said tool needs also to be installed in the global environment of `Composer`:

``` bash
$ composer global require friendsofphp/php-cs-fixer
```

After being installed, use the `php-cs-fixer` command in the same `Authsum` directory:

``` bash
$ cd Sample
$ php-cs-fixer fix --config=phpstyle.php
```

The specified `phpstyle.php` currently follows the [PSR-12](https://www.php-fig.org/psr/psr-12/) as the baseline of the coding style and uses [Allman](https://en.wikipedia.org/wiki/Indentation_style#Allman_style) as its indentation style.

> [!NOTE]
> Installing `PHPStan` and `PHP Coding Standards Fixer` requires a version of PHP that is `7.4` and above.

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