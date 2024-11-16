<?php

namespace Rougin\Authsum;

use Rougin\Authsum\Source\SourceInterface;
use Rougin\Authsum\Source\WithPassword;
use Rougin\Authsum\Source\WithPayload;
use Rougin\Authsum\Source\WithUsername;
use UnexpectedValueException;

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
    protected $passwordField = 'password';

    /**
     * @var array<string, string>
     */
    protected $payload = array();

    /**
     * @var \Rougin\Authsum\Source\SourceInterface
     */
    protected $source;

    /**
     * @var string
     */
    protected $usernameField = 'email';

    /**
     * @param \Rougin\Authsum\Source\SourceInterface $source
     */
    public function __construct(SourceInterface $source)
    {
        $this->source = $source;
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
     * Returns the password field.
     *
     * @return string
     */
    public function getPasswordField()
    {
        return $this->passwordField;
    }

    /**
     * Returns the password value.
     *
     * @return string
     * @throws \UnexpectedValueException
     */
    public function getPasswordValue()
    {
        $field = $this->getPasswordField();

        if (! array_key_exists($field, $this->payload))
        {
            $text = 'Field "' . $field . '" is not found from payload';

            throw new UnexpectedValueException($text);
        }

        return $this->payload[$field];
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
     * Returns the username field.
     *
     * @return string
     */
    public function getUsernameField()
    {
        return $this->usernameField;
    }

    /**
     * Returns the username value.
     *
     * @return string
     * @throws \UnexpectedValueException
     */
    public function getUsernameValue()
    {
        $field = $this->getUsernameField();

        if (! array_key_exists($field, $this->payload))
        {
            $text = 'Field "' . $field . '" is not found from payload';

            throw new UnexpectedValueException($text);
        }

        return $this->payload[$field];
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
        $this->payload = $payload;

        if ($this->source instanceof WithPassword)
        {
            $this->source->setPasswordField($this->getPasswordField());

            $this->source->setPasswordValue($this->getPasswordValue());
        }

        if ($this->source instanceof WithPayload)
        {
            $this->source->setPayload($this->payload);
        }

        if ($this->source instanceof WithUsername)
        {
            $this->source->setUsernameField($this->getUsernameField());

            $this->source->setUsernameValue($this->getUsernameValue());
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
        $this->passwordField = $password;

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
