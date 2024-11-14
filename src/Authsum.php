<?php

namespace Rougin\Authsum;

use Rougin\Authsum\Source\SourceInterface;
use Rougin\Authsum\Source\WithPassword;
use Rougin\Authsum\Source\WithUsername;

/**
 * @package Authsum
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Authsum
{
    /**
     * @var string
     */
    protected $password;

    /**
     * @var \Rougin\Authsum\Source\SourceInterface
     */
    protected $source;

    /**
     * @var string
     */
    protected $username;

    /**
     * @param \Rougin\Authsum\Source\SourceInterface $source
     */
    public function __construct(SourceInterface $source)
    {
        $this->source = $source;

        $this->setPasswordField('password');

        $this->setUsernameField('email');
    }

    /**
     * Returns the error after validation.
     *
     * @return \Rougin\Authsum\Error
     * @throws \UnexpectedValueException
     */
    public function getError()
    {
        return $this->source->getError();
    }

    /**
     * Gets the password field.
     *
     * @return string
     */
    public function getPasswordField()
    {
        return $this->password;
    }

    /**
     * Returns the result after validation.
     *
     * @return \Rougin\Authsum\Result
     * @throws \UnexpectedValueException
     */
    public function getResult()
    {
        return $this->source->getResult();
    }

    /**
     * Gets the username field.
     *
     * @return string
     */
    public function getUsernameField()
    {
        return $this->username;
    }

    /**
     * Checks if the payload exists from the source.
     *
     * @param array<string, string> $payload
     *
     * @return boolean
     */
    public function isValid($payload)
    {
        if ($this->source instanceof WithUsername)
        {
            $username = $payload[$this->getUsernameField()];

            $this->source->setUsername($username);
        }

        if ($this->source instanceof WithPassword)
        {
            $password = $payload[$this->getPasswordField()];

            $this->source->setPassword($password);
        }

        $valid = $this->source->isValid();

        if ($valid)
        {
            $this->passed($this->getResult());
        }
        else
        {
            $this->failed($this->getError());
        }

        return $valid;
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
        $this->password = $password;

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
        $this->username = $username;

        return $this;
    }

    /**
     * Executes if the validation failed.
     *
     * @param \Rougin\Authsum\Error $error
     *
     * @return void
     */
    protected function failed(Error $error)
    {
    }

    /**
     * Executes if the validation passed.
     *
     * @param \Rougin\Authsum\Result $data
     *
     * @return void
     */
    protected function passed(Result $data)
    {
    }
}
