<?php

namespace Rougin\Authsum;

use Rougin\Authsum\Checker\CheckerInterface;

/**
 * Authentication
 *
 * @package Authsum
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Authentication
{
    const NOT_FOUND = 0;

    const INVALID = 1;

    /**
     * Authenticates the given credentials.
     *
     * @param  \Rougin\Authsum\Checker\CheckerInterface $checker
     * @param  array                                    $credentials
     * @return mixed
     */
    public function authenticate(CheckerInterface $checker, array $credentials)
    {
        if ($this->validate($credentials) === true) {
            $matched = ($match = $checker->check($credentials)) !== false;

            return ($matched) ? $this->success($match) : $this->error();
        }

        return $this->error(self::INVALID);
    }

    /**
     * Checks if the authentication has an occured error.
     *
     * @param  integer $type
     * @return mixed
     */
    protected function error($type = self::NOT_FOUND)
    {
        return $type;
    }

    /**
     * Checks if the authentication is successful.
     *
     * @param  mixed $match
     * @return mixed
     */
    protected function success($match)
    {
        return $match;
    }

    /**
     * Validates the given credentials.
     *
     * @param  array  $credentials
     * @return boolean
     */
    protected function validate(array $credentials)
    {
        $keys = array_keys($credentials);

        return isset($keys[0]) && isset($keys[1]);
    }
}
