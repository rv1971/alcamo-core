<?php

namespace alcamo\exception;

use PHPUnit\Framework\TestCase;

class UnsupportedTest extends TestCase
{
  /**
   * @dataProvider constructProvider
   */
    public function testConstruct(
        $label,
        $message,
        $code,
        $expectedMessage
    ) {
        $e = new Unsupported($label, $message, $code);

        $this->assertSame($label, $e->label);

        $this->assertSame($expectedMessage, $e->getMessage());

        $this->assertEquals($code, $e->getCode());
    }

    public function constructProvider(): array
    {
        return [
            'typical-use' => [
                'Foo',
                null,
                null,
                'Foo not supported'
            ],

            'custom-message' => [
                'special object',
                'At vero eos et accusam',
                43,
                'At vero eos et accusam'
            ],

            'extra-message' => [
                'Bar',
                '; at vero eos et accusam',
                44,
                'Bar not supported; at vero eos et accusam'
            ]
        ];
    }
}
