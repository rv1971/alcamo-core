<?php

namespace alcamo\collection;

use PHPUnit\Framework\TestCase;

class PrefixFirstMatchCollectionTest extends TestCase
{
    public function testBasics()
    {
        $a = new PrefixFirstMatchCollection(
            [
                'foo' => 'FOO',
                'bar/baz' => 'BAZ',
                'bar' => 'BAR'
            ]
        );

        $a['qux'] = 'QUX';

        $this->assertTrue(isset($a['foo']));
        $this->assertSame('FOO', $a['foo']);

        $this->assertTrue(isset($a['bar/baz/qux']));
        $this->assertSame('BAZ', $a['bar/baz/qux']);

        $this->assertTrue(isset($a['bar/qux']));
        $this->assertSame('BAR', $a['bar/qux']);

        $this->assertTrue(isset($a['qux-lorem-ipsum']));
        $this->assertSame('QUX', $a['qux-lorem-ipsum']);

        $this->assertFalse(isset($a['quux']));
        $this->assertNull($a['quux']);

        $this->assertFalse(isset($a['fo']));
        $this->assertNull($a['fo']);

        $this->assertFalse(isset($a['']));
        $this->assertNull($a['']);
    }
}
