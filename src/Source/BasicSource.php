<?php

namespace Rougin\Authsum\Source;

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
        $sameUser = $this->sourceUsername === $this->usernameValue;

        $samePass = $this->sourcePassword === $this->passwordValue;

        if ($sameUser && $samePass)
        {
            return $this->setResult();
        }

        return $this->setError();
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
