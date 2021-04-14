<?php

namespace alcamo\exception;

use PHPUnit\Framework\TestCase;

class EofTest extends TestCase
{
  /**
   * @dataProvider constructProvider
   */
    public function testConstruct(
        $objectOrLabel,
        $requestedUnits,
        $availableUnits,
        $message,
        $code,
        $expectedMessage
    ) {
        $e = new Eof(
            $objectOrLabel,
            $requestedUnits,
            $availableUnits,
            $message,
            $code
        );

        $this->assertSame($objectOrLabel, $e->objectOrLabel);

        $this->assertSame($requestedUnits, $e->requestedUnits);

        $this->assertSame($availableUnits, $e->availableUnits);

        $this->assertSame($expectedMessage, $e->getMessage());

        $this->assertEquals($code, $e->getCode());
    }

    public function constructProvider(): array
    {
        return [
            'typical-use' => [
                $this,
                null,
                null,
                '',
                0,
                'Eof in ' . self::class
            ],

            'custom-message' => [
                'special object',
                null,
                null,
                'At vero eos et accusam',
                43,
                'At vero eos et accusam'
            ],

            'extra-message' => [
                'foo',
                3,
                2,
                '; at vero eos et accusam',
                44,
                'Eof in foo: requested 3 units, available 2; at vero eos et accusam'
            ]
        ];
    }
}
