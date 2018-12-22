<?php

namespace Rougin\Authsum\Checker;

use Rougin\Authsum\Authentication;
use Rougin\Authsum\Fixture\Authenticate;

/**
 * Abstract Test Case
 *
 * @package Authsum
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Rougin\Authsum\Checker\CheckerInterface
     */
    protected $checker;

    /**
     * Sets up the checker instance.
     *
     * @return void
     */
    public function setUp()
    {
        $this->markTestSkipped('No CheckerInterface defined.');
    }

    /**
     * Tests CheckerInterface::success.
     *
     * @return void
     */
    public function testSuccessMethod()
    {
        $authentication = new Authentication;

        $this->checker->hashed(false);

        $expected = array('username' => 'rougin', 'password' => 'gutibb');

        $result = $authentication->authenticate($this->checker, $expected);

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests CheckerInterface::success with hashing.
     *
     * @return void
     */
    public function testSuccessMethodWithHashing()
    {
        $credentials = array('username' => 'hashed', 'password' => 'rougin');

        $password = '$2y$10$WNqwC2eb5WUtvOa5x9sxTuTNT8A.8VBebmZpuPMQLUUJQszylByg6';

        $authentication = new Authentication;

        $expected = array('username' => 'hashed', 'password' => $password);

        $result = $authentication->authenticate($this->checker, $credentials);

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests CheckerInterface::error.
     *
     * @return void
     */
    public function testErrorMethod()
    {
        $authentication = new Authenticate;

        $credentials = array('username' => 'testtt', 'password' => 'rougin');

        $expected = Authentication::NOT_FOUND;

        $result = $authentication->authenticate($this->checker, $credentials);

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests CheckerInterface::error with hashing.
     *
     * @return void
     */
    public function testErrorMethodWithHashing()
    {
        $authentication = new Authentication;

        $this->checker->hashed(true);

        $credentials = array('username' => 'test', 'password' => 'rougin');

        $expected = Authentication::NOT_FOUND;

        $result = $authentication->authenticate($this->checker, $credentials);

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests CheckerInterface::validate.
     *
     * @return void
     */
    public function testValidateMethod()
    {
        $authentication = new Authenticate;

        $authentication->validation(false);

        $credentials = array('username' => 'test', 'password' => 'rougin');

        $expected = Authentication::INVALID;

        $result = $authentication->authenticate($this->checker, $credentials);

        $this->assertEquals($expected, $result);
    }
}
