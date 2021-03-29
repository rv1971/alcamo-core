<?php

namespace alcamo\exception;

use PHPUnit\Framework\TestCase;

class UninitializedTest extends TestCase
{
  /**
   * @dataProvider constructProvider
   */
    public function testConstruct(
        $objectOrLabel,
        $message,
        $code,
        $expectedMessage
    ) {
        $e = new Uninitialized($objectOrLabel, $message, $code);

        $this->assertSame($objectOrLabel, $e->objectOrLabel);

        $this->assertSame($expectedMessage, $e->getMessage());

        $this->assertEquals($code, $e->getCode());
    }

    public function constructProvider(): array
    {
        return [
            'typical-use' => [
                $this,
                '',
                0,
                'Attempt to access uninitialized ' . self::class
            ],

            'custom-message' => [
                'special object',
                'At vero eos et accusam',
                43,
                'At vero eos et accusam'
            ],

            'extra-message' => [
                'foo',
                '; at vero eos et accusam',
                44,
                'Attempt to access uninitialized foo; at vero eos et accusam'
            ]
        ];
    }
}
