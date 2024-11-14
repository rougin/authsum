<?php

namespace Rougin\Authsum\Source;

/**
 * @package Authsum
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface WithPassword
{
    /**
     * Sets the password.
     *
     * @param string $password
     *
     * @return self
     */
    public function setPassword($password);
}
