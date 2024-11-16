<?php

namespace Rougin\Authsum\Source;

use Rougin\Authsum\Authsum;
use Rougin\Authsum\Testcase;

/**
 * @package Authsum
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class PdoSourceTest extends Testcase
{
    /**
     * @var \Rougin\Authsum\Authsum
     */
    protected $auth;

    /**
     * @var \Rougin\Authsum\Source\PdoSource
     */
    protected $source;

    /**
     * @return void
     */
    public function doSetUp()
    {
        $path = __DIR__ . '/../Fixture/Storage';

        $pdo = new \PDO('sqlite:' . $path . '/athsm.s3db');

        $source = new PdoSource($pdo);

        $this->auth = new Authsum($source);

        $this->source = $source;
    }

    /**
     * @return void
     */
    public function test_invalid_with_error()
    {
        $expected = 'Invalid credentials given.';

        $payload = array();

        $payload['email'] = 'me@roug.in';

        $payload['password'] = 'hellowo';

        $valid = $this->auth->isValid($payload);

        $actual = $this->auth->getError()->getText();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_invalid_with_pdo_error()
    {
        $expected = 'SQLSTATE[HY000]: General error: 1 no such table: test';

        $payload = array();

        $payload['email'] = 'me@roug.in';

        $payload['password'] = 'hellowo';

        $this->source->setTableName('test');

        $auth = new Authsum($this->source);

        $valid = $auth->isValid($payload);

        $actual = $auth->getError()->getText();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_invalid_without_result()
    {
        $this->doExpectExceptionMessage('Validation failed');

        $payload = array('email' => 'me@roug.in');

        $payload['password'] = 'hello';

        $this->auth->isValid($payload);

        $this->auth->getResult()->getText();
    }

    /**
     * @return void
     */
    public function test_valid_with_result()
    {
        $expected = 'Credentials matched!';

        $payload = array();

        $payload['email'] = 'me@roug.in';

        $payload['password'] = 'password';

        $valid = $this->auth->isValid($payload);

        $actual = $this->auth->getResult()->getText();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_valid_without_error()
    {
        $this->doExpectExceptionMessage('Validation passed');

        $payload = array();

        $payload['email'] = 'me@roug.in';

        $payload['password'] = 'password';

        $this->auth->isValid($payload);

        $this->auth->getError()->getText();
    }

    /**
     * @return void
     */
    public function test_valid_without_hash()
    {
        $expected = 'Credentials matched!';

        $payload = array('username' => 'authsum');

        $payload['password'] = 'password!';

        $this->source->withoutHash();

        $this->source->setTableName('resus');

        $auth = new Authsum($this->source);

        $auth->setUsernameField('username');

        $valid = $auth->isValid($payload);

        $actual = $auth->getResult()->getText();

        $this->assertEquals($expected, $actual);
    }
}
