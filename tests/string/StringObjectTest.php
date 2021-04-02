<?php

namespace alcamo\string;

use PHPUnit\Framework\TestCase;
use alcamo\exception\{OutOfRange, ReadonlyViolation};

class StringObjectTest extends TestCase
{
    public function testBasics()
    {
        $text = 'Lorem ipsum dolor sit amet';

        $len = strlen($text);

        $string = new StringObject($text);

        $this->assertSame($text, (string)$string);

        $this->assertSame($len, count($string));

        $this->assertTrue(isset($string[$len - 1]));

        $this->assertFalse(isset($string[$len]));

        $this->assertSame($text[0], $string[0]);
        $this->assertSame($text[3], $string[3]);
        $this->assertSame($text[4], $string[4]);
        $this->assertSame($text[$len - 1], $string[$len - 1]);

        $string[0] = 'X';

        $string[7] = 'y';

        $string[$len - 1] = 'z';

        $this->assertSame('Xorem iysum dolor sit amez', (string)$string);

        $this->expectException(OutOfRange::class);
        $this->expectExceptionMessage(
            "Value \"$len\" out of range [0, " . ($len - 1) . "]"
        );

        $string[$len] = 'a';
    }

    public function testUnset()
    {
        $string = new StringObject('consetetur sadipscing elitr');

        $this->expectException(ReadonlyViolation::class);
        $this->expectExceptionMessage(
            'Attempt to use ' . StringObject::class . '::offsetUnset()'
        );

        unset($string[0]);
    }
}
