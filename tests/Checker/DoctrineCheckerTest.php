<?php

namespace Rougin\Authsum\Checker;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Rougin\Authsum\Authentication;
use Rougin\Authsum\Fixture\Authenticate;

/**
 * Doctrine Checker Test
 *
 * @package Authsum
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class DoctrineCheckerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Rougin\Authsum\Checker\CheckerInterface
     */
    protected $checker;

    /**
     * @var string
     */
    protected $model = 'Rougin\Authsum\Fixture\Models\DoctrineModel';

    /**
     * Sets up Doctrine and the checker.
     *
     * @return void
     */
    public function setUp()
    {
        $exists = class_exists('Doctrine\ORM\Query');

        $message = 'Doctrine ORM is not installed';

        $exists || $this->markTestSkipped($message);

        $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__), true);

        $connection = array('driver' => 'pdo_sqlite', 'charset' => 'utf8');

        $connection['path'] = __DIR__ . '/../Fixture/Database.sqlite';

        $manager = EntityManager::create($connection, $config);

        $this->checker = new DoctrineChecker($manager, $this->model);
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

    /**
     * Tests CheckerInterface::success using accessor.
     *
     * @return void
     */
    public function testSuccessMethodAndAccessorMethod()
    {
        $credentials = array('username' => 'test', 'password' => 'test');

        $this->checker->accessor('getPasswordCustom');

        $authentication = new Authentication;

        $result = $authentication->authenticate($this->checker, $credentials);

        $this->assertInstanceOf($this->model, $result);
    }
}
