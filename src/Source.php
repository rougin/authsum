<?php

namespace Rougin\Authsum;

use Rougin\Authsum\Source\SourceInterface;
use UnexpectedValueException;

/**
 * @package Authsum
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
abstract class Source implements SourceInterface
{
    /**
     * @var \Rougin\Authsum\Error|null
     */
    protected $error = null;

    /**
     * @var \Rougin\Authsum\Result|null
     */
    protected $result = null;

    /**
     * Returns the error after validation.
     *
     * @return \Rougin\Authsum\Error
     * @throws \UnexpectedValueException
     */
    public function getError()
    {
        if (! $this->error)
        {
            throw new UnexpectedValueException('Validation passed');
        }

        return $this->error;
    }

    /**
     * Returns the result after validation.
     *
     * @return \Rougin\Authsum\Result
     * @throws \UnexpectedValueException
     */
    public function getResult()
    {
        if (! $this->result)
        {
            throw new UnexpectedValueException('Validation failed');
        }

        return $this->result;
    }
}
