<?php

namespace Rougin\Authsum;

/**
 * @package Authsum
 *
 * @author Rougin Gutib <rougingutib@gmail.com>
 */
class ResultTest extends Testcase
{
    /**
     * @return void
     */
    public function test_defined_field()
    {
        $expected = 'Rougin Gutib';

        $result = new Result;

        $result->setField('name', 'Rougin Gutib');

        /** @var string */
        $actual = $result->getField('name');

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return void
     */
    public function test_undefined_field()
    {
        $expected = 'Field "age" not found';

        $this->expectExceptionMessage($expected);

        $result = new Result;

        $result->setField('name', 'Rougin Gutib');

        $result->getField('age');
    }
}
