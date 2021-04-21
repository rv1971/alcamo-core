<?php

namespace alcamo\collection;

use PHPUnit\Framework\TestCase;

class ReadonlyPrefixSetTest extends TestCase
{
    /**
     * @dataProvider constructProvider
     */
    public function testConstruct($prefixText, $expectedCount, $expectedPcre)
    {
        $set = ReadonlyPrefixSet::newFromString($prefixText);

        $this->assertSame($expectedCount, count($set));
        $this->assertSame($expectedPcre, $set->getPcre());
    }

    public function constructProvider()
    {
        return [
            [
                "a\nb\t ~~~ foo/bar 123",
                5,
                '~a.*|b.*|\~\~\~.*|foo/bar.*|123.*~A'
            ]
        ];
    }

    /**
     * @dataProvider containsProvider
     */
    public function testContains($prefixText, $item, $expectedResult)
    {
        $set = ReadonlyPrefixSet::newFromString($prefixText);

        $this->assertSame($expectedResult, $set->contains($item));
    }

    public function containsProvider()
    {
        return [
            [ 'foo /bar~', 'foobar', true ],
            [ 'foo /bar~', 'foo', true ],
            [ 'foo /bar~', 'fo', false ],
            [ 'foo /bar~', 'foxbar', false ],
            [ 'foo /bar~', '/bar~123', true ],
            [ 'foo /bar~', 'bar~123', false ]
        ];
    }
}
