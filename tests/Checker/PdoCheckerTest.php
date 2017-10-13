<?php

namespace Rougin\Authsum\Checker;

use Rougin\Authsum\Authentication;

/**
 * PDO Checker Test
 *
 * @package Authsum
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
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

        $sqlite = str_replace('Checker', 'Fixtures', $path);

        $pdo = new \PDO('sqlite:' . $sqlite . 'Database.sqlite');

        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        $this->checker = new PdoChecker($pdo, 'users');
    }

    /**
     * Tests the CheckerInterface with success method.
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
     * Tests the CheckerInterface with success method and hashing.
     *
     * @return void
     */
    public function testSuccessMethodAndHashing()
    {
        $credentials = array('username' => 'test', 'password' => 'test');

        $authentication = new Authentication;

        $result = $authentication->authenticate($this->checker, $credentials);

        $this->assertEquals('Test', $result['name']);
    }

    /**
     * Tests the CheckerInterface with error method.
     *
     * @return void
     */
    public function testErrorMethod()
    {
        $credentials = array('username' => 'test', 'password' => 'rougin');

        $authentication = new \Rougin\Authsum\Fixtures\Authenticate;

        $result = $authentication->authenticate($this->checker, $credentials);

        $this->assertEquals(Authentication::NOT_FOUND, $result);
    }

    /**
     * Tests the CheckerInterface with validate method.
     *
     * @return void
     */
    public function testValidateMethod()
    {
        $credentials = array('username' => 'rougin', 'password' => 'rougin');

        $authentication = new \Rougin\Authsum\Fixtures\Authenticate;

        $authentication->validation(false);

        $result = $authentication->authenticate($this->checker, $credentials);

        $this->assertEquals(Authentication::INVALID, $result);
    }
}
