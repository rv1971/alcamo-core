<?php

namespace alcamo\exception;

use PHPUnit\Framework\TestCase;

class OutOfRangeTest extends TestCase
{
    /**
     * @dataProvider constructProvider
     */
    public function testConstruct(
        $value,
        $lowerBound,
        $upperBound,
        $message,
        $code,
        $expectedMessage
    ) {
        $e = new OutOfRange($value, $lowerBound, $upperBound, $message, $code);

        $this->assertSame($value, $e->value);

        $this->assertSame($lowerBound, $e->lowerBound);

        $this->assertSame($upperBound, $e->upperBound);

        $this->assertSame($expectedMessage, $e->getMessage());

        $this->assertEquals($code, $e->getCode());
    }

    public function constructProvider(): array
    {
        return [
            'typical-use' => [
                0,
                1,
                2,
                null,
                null,
                "Value \"0\" out of range [1, 2]"
            ],

            'custom-message' => [
                42,
                0,
                16,
                'At vero eos et accusam',
                43,
                'At vero eos et accusam'
            ],

            'extra-message' => [
                42,
                -15,
                16,
                '; at vero eos et accusam',
                44,
                "Value \"42\" out of range [-15, 16]; at vero eos et accusam"
            ]
        ];
    }

    /**
     * @dataProvider throwIfOutsideProvider
     */
    public function testThrowIfOutside(
        $value,
        $lowerBound,
        $upperBound,
        $message,
        $code,
        $expectedMessage
    ) {
        try {
            OutOfRange::throwIfOutside(
                $value,
                $lowerBound,
                $upperBound,
                $message,
                $code
            );
        } catch (OutOfRange $e) {
            $this->assertSame($value, $e->value);

            $this->assertSame($lowerBound, $e->lowerBound);

            $this->assertSame($upperBound, $e->upperBound);

            $this->assertSame($expectedMessage, $e->getMessage());

            $this->assertEquals($code, $e->getCode());

            return;
        }

        $this->assertTrue($value >= $lowerBound && $value <= $upperBound);

        $this->assertNull($expectedMessage);
    }

    public function throwIfOutsideProvider()
    {
        return [
            'no-exception' => [ 1, 0, 2, null, null, null ],
            'below' => [
                -1,
                0,
                10,
                '; lorem ipsum',
                42,
                'Value "-1" out of range [0, 10]; lorem ipsum'
            ],
            'above' => [
                100, 1, 99, null, null, 'Value "100" out of range [1, 99]'
            ]
        ];
    }
}
