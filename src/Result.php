<?php

namespace Rougin\Authsum;

use UnexpectedValueException;

/**
 * @package Authsum
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Result
{
    /**
     * @var array<string, mixed>
     */
    protected $data = array();

    /**
     * @var string|null
     */
    protected $text = null;

    /**
     * @return string|null
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getField($key)
    {
        if (! $this->hasField($key))
        {
            $text = 'Field "' . $key . '" not found';

            throw new UnexpectedValueException($text);
        }

        return $this->data[$key];
    }

    /**
     * @param string $key
     *
     * @return boolean
     */
    public function hasField($key)
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return self
     */
    public function setField($key, $value)
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * @param string $text
     *
     * @return self
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }
}
