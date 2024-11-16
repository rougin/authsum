<?php

namespace Rougin\Authsum\Source;

/**
 * @package Authsum
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface WithPayload
{
    /**
     * Sets the prepared payload.
     *
     * @param array<string, string> $payload
     *
     * @return self
     */
    public function setPayload($payload);
}
