<?php

namespace alcamo\exception;

use PHPUnit\Framework\TestCase;

class LockedTest extends TestCase
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
        $e = new Locked($objectOrLabel, $message, $code);

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
                'Attempt to modify locked ' . self::class
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
                'Attempt to modify locked foo; at vero eos et accusam'
            ]
        ];
    }
}
