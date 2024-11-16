<?php

namespace Rougin\Authsum\Source;

/**
 * @package Authsum
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
interface JwtParserInterface
{
    /**
     * Parses the token string.
     *
     * @param string $token
     *
     * @return array<string, mixed>
     */
    public function parse($token);
}
