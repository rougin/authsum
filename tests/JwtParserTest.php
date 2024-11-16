<?php

namespace Rougin\Authsum;

use Rougin\Authsum\Source\JwtParser;

/**
 * @package Authsum
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class JwtParserTest extends Testcase
{
    /**
     * @return void
     */
    public function test_parse_with_error()
    {
        $text = 'Unable to parse an invalid token';

        $this->doExpectExceptionMessage($text);

        $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9';

        $parser = new JwtParser;

        $parser->parse($token);
    }

    /**
     * @return void
     */
    public function test_parse_jwt_token()
    {
        $expected = array('id' => 1234);

        $expected['name'] = 'John Doe';

        $expected['sub'] = '1234567890';

        $expected['iat'] = 1516239022;

        $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyLCJpZCI6MTIzNH0.9q_vOzSSAQMJP04Yp4L300rWnaI0lNOs4BAs0jl1CQU';

        $parser = new JwtParser;

        $actual = $parser->parse($token);

        $this->assertEquals($expected, $actual);
    }
}
