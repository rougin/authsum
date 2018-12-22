<?php

namespace Rougin\Authsum\Checker;

use Rougin\Authsum\Authentication;
use Rougin\Authsum\Fixture\Authenticate;

/**
 * PDO Checker Test
 *
 * @package Authsum
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class PdoCheckerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Rougin\Authsum\CheckerInterface
     */
    protected $checker;

    /**
     * Sets up PDO and the checker.
     *
     * @return void
     */
    public function setUp()
    {
        $path = __DIR__ . DIRECTORY_SEPARATOR;

        $sqlite = str_replace('Checker', 'Fixture', $path);

        $pdo = new \PDO('sqlite:' . $sqlite . 'Database.sqlite');

        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $this->checker = new PdoChecker($pdo, 'users');
    }

    /**
     * Tests CheckerInterface::success.
     *
     * @return void
     */
    public function testSuccessMethod()
    {
        $credentials = array('username' => 'rougin', 'password' => 'rougin');

        $this->checker->hashed(false);

        $authentication = new Authentication;

        $result = $authentication->authenticate($this->checker, $credentials);

        $this->assertEquals('Rougin', $result['name']);
    }

    /**
     * Tests CheckerInterface::success with hashing.
     *
     * @return void
     */
    public function testSuccessMethodWithHashing()
    {
        $credentials = array('username' => 'test', 'password' => 'test');

        $authentication = new Authentication;

        $result = $authentication->authenticate($this->checker, $credentials);

        $this->assertEquals('Test', $result['name']);
    }

    /**
     * Tests CheckerInterface::error.
     *
     * @return void
     */
    public function testErrorMethod()
    {
        $credentials = array('username' => 'test', 'password' => 'rougin');

        $authentication = new Authenticate;

        $result = $authentication->authenticate($this->checker, $credentials);

        $this->assertEquals(Authentication::NOT_FOUND, $result);
    }

    /**
     * Tests CheckerInterface::validate.
     *
     * @return void
     */
    public function testValidateMethod()
    {
        $credentials = array('username' => 'rougin', 'password' => 'rougin');

        $authentication = new Authenticate;

        $authentication->validation(false);

        $result = $authentication->authenticate($this->checker, $credentials);

        $this->assertEquals(Authentication::INVALID, $result);
    }
}
