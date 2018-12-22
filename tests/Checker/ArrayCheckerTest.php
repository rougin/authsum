<?php

namespace Rougin\Authsum\Checker;

use Rougin\Authsum\Authentication;
use Rougin\Authsum\Fixture\Authenticate;

/**
 * Array Checker Test
 *
 * @package Authsum
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class ArrayCheckerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Rougin\Authsum\Checker\CheckerInterface
     */
    protected $checker;

    /**
     * Setups the specified instances.
     *
     * @return void
     */
    public function setUp()
    {
        $items = array();

        array_push($items, array('username' => 'rougin', 'password' => 'rougin'));
        array_push($items, array('username' => 'roycee', 'password' => 'roycee'));
        array_push($items, array('username' => 'gutibb', 'password' => 'gutibb'));
        array_push($items, array('username' => 'testtt', 'password' => 'testtt'));
        array_push($items, array('username' => 'rougin', 'password' => 'gutibb'));
        array_push($items, array('username' => 'hashed', 'password' => '$2y$10$WNqwC2eb5WUtvOa5x9sxTuTNT8A.8VBebmZpuPMQLUUJQszylByg6'));

        $this->checker = new ArrayChecker($items);
    }

    /**
     * Tests CheckerInterface::success.
     *
     * @return void
     */
    public function testSuccessMethod()
    {
        $credentials = array('username' => 'rougin', 'password' => 'gutibb');

        $authentication = new Authentication;

        $this->checker->hashed(false);

        $result = $authentication->authenticate($this->checker, $credentials);

        $this->assertEquals($credentials, $result);
    }

    /**
     * Tests CheckerInterface::success with hashing.
     *
     * @return void
     */
    public function testSuccessMethodWithHashing()
    {
        $credentials = array('username' => 'hashed', 'password' => 'rougin');

        $hashed = array('username' => 'hashed', 'password' => '$2y$10$WNqwC2eb5WUtvOa5x9sxTuTNT8A.8VBebmZpuPMQLUUJQszylByg6');

        $authentication = new Authentication;

        $result = $authentication->authenticate($this->checker, $credentials);

        $this->assertEquals($hashed, $result);
    }

    /**
     * Tests CheckerInterface::error.
     *
     * @return void
     */
    public function testErrorMethod()
    {
        $credentials = array('username' => 'testtt', 'password' => 'rougin');

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
        $credentials = array('username' => 'test', 'password' => 'rougin');

        $authentication = new Authenticate;

        $authentication->validation(false);

        $result = $authentication->authenticate($this->checker, $credentials);

        $this->assertEquals(Authentication::INVALID, $result);
    }
}
