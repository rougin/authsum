<?php

namespace Rougin\Authsum\Checker;

/**
 * Checker Interface
 *
 * @package Authsum
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
interface CheckerInterface
{
    /**
     * Checks if exists in the said collection.
     *
     * @param  array $credentials
     * @return boolean|mixed
     */
    public function check(array $credentials);

    /**
     * Checks if the password field is hashed.
     *
     * @param  boolean $hashed
     * @return self
     */
    public function hashed($hashed = true);
}
