<?php

namespace Rougin\Authsum\Source;

use Rougin\Authsum\Authsum;
use Rougin\Authsum\Testcase;

/**
 * @package Authsum
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class BasicSourceTest extends Testcase
{
    /**
     * @var \Rougin\Authsum\Authsum
     */
    protected $auth;

    /**
     * @return void
     */
    public function doSetUp()
    {
        $user = 'rougingutib@gmail.com';

        $pass = 'irttoafecyhtisihst';

        $source = new BasicSource($user, $pass);

        $this->auth = new Authsum($source);
    }

    /**
     * @return void
     */
    public function test_invalid_with_error()
    {
        $expected = 'Invalid credentials given.';

        $payload = array();

        $payload['email'] = 'rougingutib@gmail.com';

        $payload['password'] = 'itftscioeirathhsty';

        $valid = $this->auth->isValid($payload);

        $actual = $this->auth->getError()->getText();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_invalid_without_result()
    {
        $this->doExpectExceptionMessage('Validation failed');

        $payload = array('email' => 'me@roug.in');

        $payload['password'] = 'ethstyrhttoisiac';

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

        $payload['email'] = 'rougingutib@gmail.com';

        $payload['password'] = 'irttoafecyhtisihst';

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

        $payload['email'] = 'rougingutib@gmail.com';

        $payload['password'] = 'irttoafecyhtisihst';

        $this->auth->isValid($payload);

        $this->auth->getError()->getText();
    }
}
