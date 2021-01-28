<?php

namespace alcamo\collection;

use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    public function testBasics()
    {
        $data = [ 'foo', 'bar', 'baz' ];

        $a = new Collection($data);

        $this->assertSame(count($data), count($a));

        $this->assertSame('foo', $a->first());

        $this->assertSame('baz', $a->last());

        $this->assertTrue($a->contains('foo'));

        $this->assertFalse($a->contains('FOO'));

        $data2 = [];

        foreach ($a as $key => $value) {
            $data2[$value] = $key;
        }

        $data2 = array_flip($data2);

        $this->assertEquals($data, $data2);

        $this->assertSame('bar', $a[1]);

        $this->assertTrue(isset($a[2]));

        $this->assertFalse(isset($a[3]));

        $this->assertFalse(isset($a[3]));

        $a[3] = 'qux';

        $this->assertSame(count($data) + 1, count($a));

        $this->assertSame('foo', $a->first());

        $this->assertSame('qux', $a->last());

        $this->assertSame('qux', $a[3]);

        unset($a[0]);

        $this->assertNull($a[0]);

        $this->assertSame('bar', $a->first());

        $b = new Collection();

        $this->assertSame(0, count($b));

        $this->assertSame(null, $b->first());

        $this->assertSame(null, $b->last());
    }
}
