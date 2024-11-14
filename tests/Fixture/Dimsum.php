<?php

namespace Rougin\Authsum\Fixture;

use Rougin\Authsum\Authsum;
use Rougin\Authsum\Error;
use Rougin\Authsum\Result;

/**
 * @package Authsum
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class Dimsum extends Authsum
{
    /**
     * @param \Rougin\Authsum\Error $error
     *
     * @return void
     */
    protected function failed(Error $error)
    {
        throw new \Exception((string) $error->getText());
    }

    /**
     * @param \Rougin\Authsum\Result $data
     *
     * @return void
     */
    protected function passed(Result $data)
    {
        throw new \Exception((string) $data->getText());
    }
}
