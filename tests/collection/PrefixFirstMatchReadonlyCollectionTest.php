<?php

namespace alcamo\collection;

use alcamo\exception\ReadonlyViolation;
use PHPUnit\Framework\TestCase;

class PrefixFirstMatchReadonlyCollectionTest extends TestCase
{
    public function testBasics()
    {
        $a = new PrefixFirstMatchReadonlyCollection(
            [
                'foo' => 'FOO',
                'bar/baz' => 'BAZ',
                'bar' => 'BAR'
            ]
        );

        $this->assertTrue(isset($a['foo']));
        $this->assertSame('FOO', $a['foo']);

        $this->assertTrue(isset($a['bar/baz/qux']));
        $this->assertSame('BAZ', $a['bar/baz/qux']);

        $this->assertTrue(isset($a['bar/qux']));
        $this->assertSame('BAR', $a['bar/qux']);

        $this->assertFalse(isset($a['fo']));
        $this->assertNull($a['fo']);

        $this->assertFalse(isset($a['']));
        $this->assertNull($a['']);

        $this->expectException(ReadonlyViolation::class);

        $a['qux'] = 'QUX';
    }
}
