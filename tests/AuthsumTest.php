<?php

namespace Rougin\Authsum;

use Rougin\Authsum\Fixture\Dimsum;
use Rougin\Authsum\Source\BasicSource;

/**
 * @package Authsum
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class AuthsumTest extends Testcase
{
    /**
     * @var \Rougin\Authsum\Source\SourceInterface
     */
    protected $source;

    /**
     * @return void
     */
    public function doSetUp()
    {
        $this->source = new BasicSource('hello', 'olleh');
    }

    /**
     * @return void
     */
    public function test_change_payload_fields()
    {
        $expected = 'Credentials matched!';

        $auth = new Authsum($this->source);

        $auth->setUsernameField('username');

        $auth->setPasswordField('pass');

        $payload = array('username' => 'hello', 'pass' => 'olleh');

        $valid = $auth->isValid($payload);

        $actual = $auth->getResult()->getText();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_failed_event()
    {
        $expected = 'Invalid credentials given';

        $this->doExpectExceptionMessage($expected);

        $auth = new Dimsum($this->source);

        $payload = array('email' => 'hello', 'password' => 'ehllo');

        $auth->isValid($payload);
    }

    /**
     * @return void
     */
    public function test_passed_event()
    {
        $expected = 'Credentials matched!';

        $this->doExpectExceptionMessage($expected);

        $auth = new Dimsum($this->source);

        $payload = array('email' => 'hello', 'password' => 'olleh');

        $auth->isValid($payload);
    }

    /**
     * @return void
     */
    public function test_password_not_found_from_payload()
    {
        $expected = 'Field "password" is not found from payload';

        $this->doExpectExceptionMessage($expected);

        $auth = new Authsum($this->source);

        $auth->isValid(array('email' => 'hello'));
    }

    /**
     * @return void
     */
    public function test_username_not_found_from_payload()
    {
        $expected = 'Field "email" is not found from payload';

        $this->doExpectExceptionMessage($expected);

        $auth = new Authsum($this->source);

        $auth->isValid(array('password' => 'olleh'));
    }
}
