<?php

namespace Rougin\Authsum\Source;

use Rougin\Authsum\Error;
use Rougin\Authsum\Result;
use Rougin\Authsum\Source;

/**
 * @package Authsum
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class BasicSource extends Source implements WithUsername, WithPassword
{
    /**
     * @var string
     */
    protected $password;

    /**
     * @var array<string, string>
     */
    protected $payload = array();

    /**
     * @var string
     */
    protected $username;

    /**
     * @param string $username
     * @param string $password
     */
    public function __construct($username, $password)
    {
        $this->username = $username;

        $this->password = $password;
    }

    /**
     * Checks if it exists from the source.
     *
     * @return boolean
     */
    public function isValid()
    {
        $sameUsername = $this->payload['username'] === $this->username;

        $samePassword = $this->payload['password'] === $this->password;

        $valid = $sameUsername && $samePassword;

        if ($valid)
        {
            $result = new Result;

            $result->setText('Credentials matched!');

            $this->result = $result;
        }
        else
        {
            $error = new Error;

            $error->setText('Invalid credentials given.');

            $this->error = $error;
        }

        return $valid;
    }

    /**
     * Sets the password.
     *
     * @param string $password
     *
     * @return self
     */
    public function setPassword($password)
    {
        $this->payload['password'] = $password;

        return $this;
    }

    /**
     * Sets the username.
     *
     * @param string $username
     *
     * @return self
     */
    public function setUsername($username)
    {
        $this->payload['username'] = $username;

        return $this;
    }
}
