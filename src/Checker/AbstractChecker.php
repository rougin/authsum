<?php

namespace Rougin\Authsum\Checker;

/**
 * Abstract Checker
 *
 * @package Authsum
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
abstract class AbstractChecker implements CheckerInterface
{
    /**
     * @var boolean
     */
    protected $hashed = true;

    /**
     * Checks if hashed will be used in the password field.
     *
     * @param  boolean $hashed
     * @return self
     */
    public function hashed($hashed = true)
    {
        $this->hashed = $hashed;

        return $this;
    }
}
