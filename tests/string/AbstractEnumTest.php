<?php

namespace alcamo\string;

use PHPUnit\Framework\TestCase;
use alcamo\exception\{InvalidEnumerator};

class Foo extends AbstractEnum
{
    public const VALUES = [ 'foo', 'bar-baz', 'qux1', '42' ];
}

class AbstractEnumTest extends TestCase
{
    /**
     * @dataProvider basicsProvider
     */
    public function testBasics($text)
    {
        $foo = new Foo($text);

        $this->assertSame($text, (string)$foo);
        $this->assertSame($text[1], $foo[1]);
    }

    public function basicsProvider()
    {
        return [
            'foo' => [ 'foo' ],
            'bar-baz' => [ 'bar-baz' ],
            'qux1' => [ 'qux1' ],
            '42' => [ '42' ]
        ];
    }

    public function testConstructException()
    {
        $this->expectException(InvalidEnumerator::class);
        $this->expectExceptionMessage(
            'Invalid value "Foo", expected one of: "foo", "bar-baz", "qux1", "42"'
        );

        new Foo('Foo');
    }
}
