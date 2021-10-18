<?php

namespace alcamo\xml\exception;

use PHPUnit\Framework\TestCase;

class UnknownNamespacePrefixTest extends TestCase
{
    /**
     * @dataProvider basicsProvider
     */
    public function testBasics($context, $expectedMessage)
    {
        $e = new UnknownNamespacePrefix(null, 0, null, $context);

        $this->assertSame($expectedMessage, $e->getMessage());
    }

    public function basicsProvider()
    {
        return [
            [
                [ 'prefix' => 'foo' ], 'Unknown namespace prefix "foo"'
            ]
        ];
    }
}
