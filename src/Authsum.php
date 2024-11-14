<?php

namespace Rougin\Authsum;

use Rougin\Authsum\Source\SourceInterface;

/**
 * @package Authsum
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Authsum
{
    /**
     * @var \Rougin\Authsum\Source\SourceInterface
     */
    protected $source;

    /**
     * @param \Rougin\Authsum\Source\SourceInterface $source
     */
    public function __construct(SourceInterface $source)
    {
        $this->source = $source;
    }

    /**
     * Returns the error after validation.
     *
     * @return \Rougin\Authsum\Error
     * @throws \UnexpectedValueException
     */
    public function getError()
    {
        return $this->source->getError();
    }

    /**
     * Returns the result after validation.
     *
     * @return \Rougin\Authsum\Result
     * @throws \UnexpectedValueException
     */
    public function getResult()
    {
        return $this->source->getResult();
    }

    /**
     * Executes if the validation failed.
     *
     * @param \Rougin\Authsum\Error $error
     *
     * @return void
     */
    protected function failed(Error $error)
    {
    }

    /**
     * Checks if the payload exists from the source.
     *
     * @param array<string, string> $payload
     *
     * @return boolean
     */
    public function isValid($payload)
    {
        $valid = $this->source->isValid();

        if ($valid)
        {
            $this->passed($this->getResult());
        }
        else
        {
            $this->failed($this->getError());
        }

        return $valid;
    }

    /**
     * Executes if the validation passed.
     *
     * @param \Rougin\Authsum\Result $data
     *
     * @return void
     */
    protected function passed(Result $data)
    {
    }
}
