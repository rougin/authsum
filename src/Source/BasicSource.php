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
    protected $passwordField = 'password';

    /**
     * @var string
     */
    protected $passwordValue;

    /**
     * @var string
     */
    protected $sourcePassword;

    /**
     * @var string
     */
    protected $sourceUsername;

    /**
     * @var string
     */
    protected $usernameField = 'username';

    /**
     * @var string
     */
    protected $usernameValue;

    /**
     * @param string $username
     * @param string $password
     */
    public function __construct($username, $password)
    {
        $this->sourceUsername = $username;

        $this->sourcePassword = $password;
    }

    /**
     * Checks if it exists from the source.
     *
     * @return boolean
     */
    public function isValid()
    {
        $sameUsername = $this->sourceUsername === $this->usernameValue;

        $samePassword = $this->sourcePassword === $this->passwordValue;

        if ($sameUsername && $samePassword)
        {
            $result = new Result;

            $result->setText('Credentials matched!');

            $this->result = $result;

            return true;
        }

        $error = new Error;

        $error->setText('Invalid credentials given.');

        $this->error = $error;

        return false;
    }

    /**
     * Sets the password field.
     *
     * @param string $password
     *
     * @return self
     */
    public function setPasswordField($password)
    {
        $this->passwordField = $password;

        return $this;
    }

    /**
     * Sets the password value.
     *
     * @param string $password
     *
     * @return self
     */
    public function setPasswordValue($password)
    {
        $this->passwordValue = $password;

        return $this;
    }

    /**
     * Sets the username field.
     *
     * @param string $username
     *
     * @return self
     */
    public function setUsernameField($username)
    {
        $this->usernameField = $username;

        return $this;
    }

    /**
     * Sets the username value.
     *
     * @param string $username
     *
     * @return self
     */
    public function setUsernameValue($username)
    {
        $this->usernameValue = $username;

        return $this;
    }
}
