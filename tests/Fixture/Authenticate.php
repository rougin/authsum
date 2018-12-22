<?php

namespace Rougin\Authsum\Fixture;

/**
 * Authenticate
 *
 * @package Authsum
 * @author  Rougin Gutib <rougingutib@gmail.com>
 */
class Authenticate extends \Rougin\Authsum\Authentication
{
    /**
     * @var boolean
     */
    protected $validate = true;

    /**
     * Validates the given credentials.
     *
     * @param  array  $credentials
     * @return boolean
     */
    protected function validate(array $credentials)
    {
        return $this->validate;
    }

    /**
     * Sets the validation of the authentication.
     *
     * @param  boolean $validate
     * @return self
     */
    public function validation($validate = true)
    {
        $this->validate = $validate;

        return $this;
    }
}
