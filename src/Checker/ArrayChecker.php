<?php

namespace Rougin\Authsum\Checker;

/**
 * Array Checker
 *
 * @package Authsum
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class ArrayChecker extends AbstractChecker implements CheckerInterface
{
    /**
     * @var array
     */
    protected $items = array();

    /**
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
        list($fields, $values) = array(array_keys($credentials), array_values($credentials));

        $columns = array_map(function ($item) use ($fields) {
            $column = $fields[0];

            return $item[$column];
        }, $this->items);

        $index = array_search($credentials[$fields[0]], $columns);

        if ($this->hashed === true) {
            $verified = password_verify($values[1], $this->items[$index][$fields[1]]);
        } else {
            $verified = $this->items[$index][$fields[1]] === $credentials[$fields[1]];
        }

        return ($verified) ? $this->items[$index] : false;
    }
}
