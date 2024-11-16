<?php

namespace Rougin\Authsum\Source;

/**
 * NOTE: This class only parses the JSON web token without validating it.
 * Kindly use third-party JSON web token parsed instead (e.g., lcobucci/jwt).
 *
 * @link https://stackoverflow.com/q/38552003
 *
 * @package Authsum
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class JwtParser implements JwtParserInterface
{
    /**
     * @link https://www.converticacommerce.com/support-maintenance/security/php-one-liner-decode-jwt-json-web-tokens
     *
     * Parses the token string.
     *
     * @param string $token
     *
     * @return array<string, mixed>
     */
    public function parse($token)
    {
        $items = explode('.', $token);

        $parsed = '';

        if (isset($items[1]))
        {
            $parsed = str_replace('-', '+', $items[1]);
        }

        $parsed = str_replace('_', '/', $parsed);

        $decoded = base64_decode($parsed);

        /** @var array<string, mixed>|null */
        $result = json_decode($decoded, true);

        if ($result)
        {
            return $result;
        }

        $text = 'Unable to parse an invalid token';

        throw new \UnexpectedValueException($text);
    }
}
