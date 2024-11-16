<?php

namespace Rougin\Authsum\Source;

use Rougin\Authsum\Source;

/**
 * @package Authsum
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class JwtSource extends Source implements WithUsername, WithPayload
{
    /**
     * @var \Rougin\Authsum\Source\JwtParserInterface
     */
    protected $parser;

    /**
     * @var array<string, string>
     */
    protected $payload = array();

    /**
     * @var string
     */
    protected $usernameField;

    /**
     * @var string
     */
    protected $usernameValue;

    /**
     * @var string
     */
    protected $token = 'token';

    /**
     * @param \Rougin\Authsum\Source\JwtParserInterface $parser
     */
    public function __construct(JwtParserInterface $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Checks if it exists from the source.
     *
     * @return boolean
     */
    public function isValid()
    {
        if (! array_key_exists($this->token, $this->payload))
        {
            return $this->setNotFound($this->token);
        }

        $token = $this->payload[$this->token];

        try
        {
            $parsed = $this->parser->parse($token);

            if (! array_key_exists($this->usernameField, $parsed))
            {
                return $this->setNotFound($this->usernameField);
            }
        }
        catch (\Exception $e)
        {
            return $this->setError($e->getMessage());
        }

        $same = $parsed[$this->usernameField] === $this->usernameValue;

        return $same ? $this->setResult() : $this->setError();
    }

    /**
     * Sets the prepared payload.
     *
     * @param array<string, string> $payload
     *
     * @return self
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;

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

    /**
     * @param string $token
     *
     * @return self
     */
    public function setTokenField($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return boolean
     */
    protected function setNotFound($name)
    {
        return $this->setError('Field "' . $name . '" not found from payload');
    }
}
