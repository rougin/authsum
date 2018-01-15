<?php

namespace Rougin\Authsum\Checker;

use Illuminate\Database\Capsule\Manager;
use Rougin\Authsum\Authentication;
use Rougin\Authsum\Fixture\Authenticate;

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
    protected $model = 'Rougin\Authsum\Fixture\Models\EloquentModel';

    /**
     * Sets up Eloquent and the checker.
     *
     * @return void
     */
    public function setUp()
    {
        $exists = class_exists('Illuminate\Database\Seeder');

        $message = 'Eloquent ORM is not installed';

        $exists || $this->markTestSkipped($message);

        $capsule = new Manager;

        $connection = array('driver' => 'sqlite', 'prefix' => '');

        $connection['database'] = __DIR__ . '/../Fixture/Database.sqlite';

        $capsule->addConnection($connection);

        $capsule->bootEloquent();

        $this->checker = new EloquentChecker($this->model);
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

        $this->assertInstanceOf($this->model, $result);
    }

    /**
     * Tests CheckerInterface::success with hashing.
     *
     * @return void
     */
    public function testSuccessMethodWithHashing()
    {
        $credentials = array('username' => 'test', 'password' => 'test');

        $this->checker->hashed(true);

        $authentication = new Authentication;

        $result = $authentication->authenticate($this->checker, $credentials);

        $this->assertInstanceOf($this->model, $result);
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
     * Tests CheckerInterface::error with hashing.
     *
     * @return void
     */
    public function testErrorMethodWithHashing()
    {
        $credentials = array('username' => 'test', 'password' => 'rougin');

        $this->checker->hashed(true);

        $authentication = new Authentication;

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
