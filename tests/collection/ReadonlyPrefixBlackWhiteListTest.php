<?php

namespace alcamo\collection;

use PHPUnit\Framework\TestCase;

class ReadonlyPrefixBlackWhiteListTest extends TestCase
{
    /**
     * @dataProvider allowsProvider
     */
    public function testAllows(
        $prefixText,
        $item,
        $expectedResult
    ) {
        $list = ReadonlyPrefixBlackWhiteList::newFromStringWithOperator(
            $prefixText
        );

        $this->assertSame($expectedResult, $list->allows($item));
    }

    public function allowsProvider()
    {
        return [
            [ 'foo /bar~', 'foobar', true ],
            [ '! foo /bar~', 'foobar', false ],
            [ 'foo /bar~', 'foo', true ],
            [ '!foo /bar~', 'foo', false ],
            [ 'foo /bar~', 'fo', false ],
            [ '!   foo /bar~', 'fo', true ],
            [ 'foo /bar~', 'foxbar', false ],
            [ '! foo /bar~', 'foxbar', true ],
            [ 'foo /bar~', '/bar~123', true ],
            [ '! foo /bar~', '/bar~123', false ],
            [ 'foo /bar~', 'bar~123', false ],
            [ '! foo /bar~', 'bar~123', true ]
        ];
    }
}
