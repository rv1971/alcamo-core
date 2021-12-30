<?php

namespace alcamo\collection;

use alcamo\exception\ReadonlyViolation;
use PHPUnit\Framework\TestCase;

class ReadonlySplObjectStorageCollectionTest extends TestCase
{
    public function testBasics()
    {
        $foo = (object)['id' => 'foo'];
        $bar = (object)['id' => 'bar'];
        $baz = (object)['id' => 'baz'];
        $qux = (object)['id' => 'qux'];

        $storage = new \SplObjectStorage();

        $storage[$foo] = 'Foo';
        $storage[$bar] = 'Bar';
        $storage[$baz] = 'Baz';

        $collection = new ReadonlySplObjectStorageCollection($storage);

        $this->assertSame(3, count($collection));

        $values = [];

        foreach ($collection as $key => $value) {
            $values[$key->id] = $value;
        }

        $this->assertSame(
            [ 'foo' => 'Foo', 'bar' => 'Bar', 'baz' => 'Baz' ],
            $values
        );

        $this->assertTrue($collection->contains($foo));
        $this->assertTrue($collection->contains($bar));
        $this->assertTrue($collection->contains($baz));
        $this->assertFalse($collection->contains($qux));

        $this->assertTrue(isset($collection[$foo]));
        $this->assertTrue(isset($collection[$bar]));
        $this->assertTrue(isset($collection[$baz]));

        $this->assertSame('Foo', $collection[$foo]);
        $this->assertSame('Bar', $collection[$bar]);
        $this->assertSame('Baz', $collection[$baz]);
    }

    public function testUnset()
    {
        $foo = (object)[];

        $storage = new \SplObjectStorage();

        $storage[$foo] = true;

        $collection = new ReadonlySplObjectStorageCollection($storage);

        $this->expectException(ReadonlyViolation::class);
        $this->expectExceptionMessage(
            'Attempt to modify readonly object <'
            . ReadonlySplObjectStorageCollection::class
            . '> in method "offsetUnset"'
        );

        unset($collection[$foo]);
    }

    public function testSet()
    {
        $foo = (object)[];

        $storage = new \SplObjectStorage();

        $storage[$foo] = true;

        $collection = new ReadonlySplObjectStorageCollection($storage);

        $this->expectException(ReadonlyViolation::class);
        $this->expectExceptionMessage(
            'Attempt to modify readonly object <'
            . ReadonlySplObjectStorageCollection::class
            . '> in method "offsetSet"'
        );

        $collection[$foo] = false;
    }
}
