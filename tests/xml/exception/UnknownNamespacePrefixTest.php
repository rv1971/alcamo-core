<?php

namespace alcamo\xml\exception;

use PHPUnit\Framework\TestCase;

class UnknownNamespacePrefixTest extends TestCase
{
  /**
   * @dataProvider constructProvider
   */
    public function testConstruct(
        $prefix,
        $validPrefixes,
        $message,
        $code,
        $expectedMessage
    ) {
        $e = new UnknownNamespacePrefix(
            $prefix,
            $validPrefixes,
            $message,
            $code
        );

        $this->assertSame($prefix, $e->prefix);

        $this->assertSame($validPrefixes, $e->validPrefixes);

        $this->assertSame($expectedMessage, $e->getMessage());

        $this->assertEquals($code, $e->getCode());
    }

    public function constructProvider(): array
    {
        return [
            'typical-use' => [
                'foo',
                null,
                null,
                null,
                'Unknown namespace prefix "foo"'
            ],

            'custom-message' => [
                'bar',
                null,
                'At vero eos et accusam',
                43,
                'At vero eos et accusam'
            ],

            'extra-message' => [
                'baz',
                [ 'foo', 'bar' ],
                '; at vero eos et accusam',
                44,
                'Unknown namespace prefix "baz", expected one of: "foo", "bar"; at vero eos et accusam'
            ]
        ];
    }
}
