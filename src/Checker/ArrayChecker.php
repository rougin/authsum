<?php

namespace Rougin\Authsum\Checker;

/**
 * Array Checker
 *
 * @package Authsum
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class ArrayChecker extends AbstractChecker implements CheckerInterface
{
    /**
     * @var array
     */
    protected $items = array();

    /**
     * Initializes the checker instance.
     *
     * @param array $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * Checks if exists in the said collection.
     *
     * @param  array $credentials
     * @return boolean|mixed
     */
    public function check(array $credentials)
    {
        $index = array_search($credentials, $this->items);

        $selected = (array) $this->items[$index];

        $result = $index !== false ? $selected : false;

        $this->hashed && $result = $this->verify($credentials);

        return $result;
    }

    /**
     * Checks the credentials with password_verify().
     *
     * @param  array   $credentials
     * @param  boolean $result
     * @return boolean|mixed
     */
    protected function verify($credentials, $result = false)
    {
        $fields = array_keys($credentials);

        $username = $credentials[$fields[0]];

        $password = $credentials[$fields[1]];

        foreach ((array) $this->items as $item) {
            $hashed = $item[$fields[1]];

            $verified = password_verify($password, $hashed);

            $exists = $username === $item[$fields[0]];

            ($verified && $exists) && $result = $item;
        }

        return $result;
    }
}
