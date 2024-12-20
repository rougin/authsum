<?php

namespace Rougin\Authsum\Source;

/**
 * @package Authsum
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface SourceInterface
{
    const CREDENTIALS_MATCHED = 'Credentials matched!';

    const CREDENTIALS_INVALID = 'Invalid credentials given.';

    /**
     * Returns the error after validation.
     *
     * @return \Rougin\Authsum\Error
     * @throws \UnexpectedValueException
     */
    public function getError();

    /**
     * Returns the result after validation.
     *
     * @return \Rougin\Authsum\Result
     * @throws \UnexpectedValueException
     */
    public function getResult();

    /**
     * Checks if it exists from the source.
     *
     * @return boolean
     */
    public function isValid();
}
