<?php

namespace alcamo\exception;

use PHPUnit\Framework\TestCase;

class AbsoluteUriNeededTest extends TestCase
{
  /**
   * @dataProvider constructProvider
   */
    public function testConstruct(
        $uri,
        $message,
        $code,
        $expectedMessage
    ) {
        $e = new AbsoluteUriNeeded($uri, $message, $code);

        $this->assertSame($uri, $e->uri);

        $this->assertSame($expectedMessage, $e->getMessage());

        $this->assertEquals($code, $e->getCode());
    }

    public function constructProvider(): array
    {
        return [
            'typical-use' => [
                'foo',
                '',
                0,
                'Relative URI "foo" given where absolute URI is needed'
            ],

            'custom-message' => [
                'baz',
                'At vero eos et accusam',
                43,
                'At vero eos et accusam'
            ],

            'extra-message' => [
                'qux',
                '; at vero eos et accusam',
                44,
                'Relative URI "qux" given where absolute URI is needed; at vero eos et accusam'
            ]
        ];
    }
}
