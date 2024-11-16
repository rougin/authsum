<?php

namespace Rougin\Authsum\Source;

/**
 * @package Authsum
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface WithUsername
{
    /**
     * Sets the username field.
     *
     * @param string $username
     *
     * @return self
     */
    public function setUsernameField($username);

    /**
     * Sets the username.
     *
     * @param string $username
     *
     * @return self
     */
    public function setUsernameValue($username);
}
