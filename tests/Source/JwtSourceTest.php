<?php

namespace Rougin\Authsum\Source;

use Rougin\Authsum\Authsum;
use Rougin\Authsum\Testcase;

/**
 * @package Authsum
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class JwtSourceTest extends Testcase
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
        $this->auth = new Authsum(new JwtSource(new JwtParser));
    }

    /**
     * @return void
     */
    public function test_invalid_jwt_string()
    {
        $expected = 'Unable to parse an invalid token';

        $token = 'Loremipsumdolorsitametconsecteturadipisicingelitseddoeiusmod';

        $payload = array('token' => $token);

        $payload['email'] = 'rougingutib@gmail.com';

        $valid = $this->auth->isValid($payload);

        $actual = $this->auth->getError()->getText();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_invalid_token_field()
    {
        $expected = 'Field "test" not found from payload';

        $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IlJvdWdpbiBHdXRpYiIsImVtYWlsIjoicm91Z2luZ3V0aWJAZ21haWwuY29tIiwiaWF0IjoxNTE2MjM5MDIyfQ.wWi4Vd0QSMlHMXwYnZKd9aZc8FOREqM9LVz5bUlK7dg';

        $payload = array('token' => $token);

        $payload['email'] = 'rougin@gmail.com';

        $source = new JwtSource(new JwtParser);

        $source->setTokenField('test');

        $auth = new Authsum($source);

        $valid = $auth->isValid($payload);

        $actual = $auth->getError()->getText();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_invalid_username_field()
    {
        $expected = 'Field "email" not found from payload';

        $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IlJvdWdpbiBHdXRpYiIsImlhdCI6MTUxNjIzOTAyMn0.Uizo7oldT0fOGPhHmhm6tNsfBcOq2nnWiGStJTpyVkc';

        $payload = array('token' => $token);

        $payload['email'] = 'rougingutib@gmail.com';

        $valid = $this->auth->isValid($payload);

        $actual = $this->auth->getError()->getText();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_invalid_with_error()
    {
        $expected = 'Invalid credentials given.';

        $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IlJvdWdpbiBHdXRpYiIsImVtYWlsIjoicm91Z2luZ3V0aWJAZ21haWwuY29tIiwiaWF0IjoxNTE2MjM5MDIyfQ.wWi4Vd0QSMlHMXwYnZKd9aZc8FOREqM9LVz5bUlK7dg';

        $payload = array('token' => $token);

        $payload['email'] = 'rougin@gmail.com';

        $valid = $this->auth->isValid($payload);

        $actual = $this->auth->getError()->getText();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_valid_with_result()
    {
        $expected = 'Credentials matched!';

        $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IlJvdWdpbiBHdXRpYiIsImVtYWlsIjoicm91Z2luZ3V0aWJAZ21haWwuY29tIiwiaWF0IjoxNTE2MjM5MDIyfQ.wWi4Vd0QSMlHMXwYnZKd9aZc8FOREqM9LVz5bUlK7dg';

        $payload = array('token' => $token);

        $payload['email'] = 'rougingutib@gmail.com';

        $this->auth->setPasswordField('token');

        $valid = $this->auth->isValid($payload);

        $actual = $this->auth->getResult()->getText();

        $this->assertEquals($expected, $actual);
    }
}
