<?php

namespace alcamo\binary_data;

use PHPUnit\Framework\TestCase;
use alcamo\exception\{OutOfRange, SyntaxError};

class BcdTest extends TestCase
{
    /**
     * @dataProvider newFromIntProvider
     */
    public function testNewFromInt(
        $value,
        $minDigits,
        $allowOdd,
        $expectedString
    ) {
        $bcd = Bcd::newFromInt($value, $minDigits, $allowOdd);

        $this->assertSame($expectedString, (string)$bcd);
    }

    public function newFromIntProvider()
    {
        return [
            'simple-0' => [ 0, null, null, '00' ],
            'simple-1' => [ 1, null, null, '01' ],
            'simple-42' => [ 42, null, null, '42' ],
            'simple-123456789' => [ 123456789, null, null, '0123456789' ],
            'minDigits-0' => [ 0, 3, null, '0000' ],
            'minDigits-42' => [ 42, 8, null, '00000042' ],
            'minDigits-12345' => [ 12345, 3, null, '012345' ],
            'allowOdd-0' => [ 0, null, true, '0' ],
            'allowOdd-1' => [ 1, null, true, '1' ],
            'allowOdd-minDigits-123' => [ 123, 7, true, '0000123' ],
            'allowOdd-minDigits-1234567' => [ 1234567, 6, true, '1234567' ]
        ];
    }

    public function testConstruct()
    {
        $bcd = new Bcd(
            "12 34 56 78 90 12 34 56 78 90 12 34 56 78 90 12 34 56 78 90"
        );

        $this->assertSame(
            "1234567890123456789012345678901234567890",
            (string)$bcd
        );
    }

    public function testConstructException()
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage(
            "Syntax error in \"12A34567\" at 2: \"A34567\"; not a valid integer literal"
        );

        new Bcd('12A34567');
    }

    public function testToInt()
    {
        $this->assertSame(0, (new Bcd(''))->toInt());
        $this->assertSame(123, (new Bcd('123'))->toInt());
        $this->assertSame(456, (new Bcd('000456'))->toInt());
    }


    public function testToIntException()
    {
        $this->expectException(OutOfRange::class);
        $this->expectExceptionMessage(
            'Value "' . PHP_INT_MAX . '0" out of range [0, ' . PHP_INT_MAX
            . ']; unable to convert BCD to integer'
        );

        (new Bcd(PHP_INT_MAX . '0'))->toInt();
    }
}