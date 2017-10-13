<?php

namespace Rougin\Authsum\Checker;

use Rougin\Authsum\Authentication;

/**
 * Eloquent Checker Test
 *
 * @package Authsum
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class EloquentCheckerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Rougin\Authsum\CheckerInterface
     */
    protected $checker;

    /**
     * @var string
     */
    protected $model = 'Rougin\Authsum\Fixtures\Models\EloquentModel';

    /**
     * Sets up Eloquent and the checker.
     *
     * @return void
     */
    public function setUp()
    {
        class_exists('Illuminate\Database\Seeder') || $this->markTestSkipped('Eloquent is not installed');

        $capsule = new \Illuminate\Database\Capsule\Manager;

        $connection = array('driver' => 'sqlite', 'prefix' => '');

        $connection['database'] = __DIR__ . '/../Fixtures/Database.sqlite';

        $capsule->addConnection($connection);

        $capsule->bootEloquent();

        $this->checker = new EloquentChecker($this->model);
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

        $this->assertInstanceOf($this->model, $result);
    }

    /**
     * Tests the CheckerInterface with success method and hashing.
     *
     * @return void
     */
    public function testSuccessMethodAndHashing()
    {
        $credentials = array('username' => 'test', 'password' => 'test');

        $this->checker->hashed(true);

        $authentication = new Authentication;

        $result = $authentication->authenticate($this->checker, $credentials);

        $this->assertInstanceOf($this->model, $result);
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
