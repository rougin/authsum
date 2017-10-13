<?php

namespace Rougin\Authsum\Checker;

use Rougin\Authsum\Authentication;

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
    protected $model = 'Rougin\Authsum\Fixtures\Models\DoctrineModel';

    /**
     * Sets up Doctrine and the checker.
     *
     * @return void
     */
    public function setUp()
    {
        class_exists('Doctrine\ORM\Query') || $this->markTestSkipped('Doctrine ORM is not installed');

        $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(array(__DIR__), true);

        $connection = array('driver' => 'pdo_sqlite', 'charset' => 'utf8');

        $connection['path'] = __DIR__ . '/../Fixtures/Database.sqlite';

        $manager = \Doctrine\ORM\EntityManager::create($connection, $config);

        $this->checker = new DoctrineChecker($manager, $this->model);
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

    /**
     * Tests the CheckerInterface with success method using accessor.
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
