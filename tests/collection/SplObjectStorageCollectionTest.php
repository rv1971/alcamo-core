<?php

namespace alcamo\collection;

use PHPUnit\Framework\TestCase;

class SplObjectStorageCollectionTest extends TestCase
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

        $collection = new SplObjectStorageCollection($storage);

        $this->assertSame(3, count($collection));

        $values = [];

        foreach ($collection as $key => $value) {
            foreach (clone $collection as $dummy) {
                /* do nothing, but show that the clone is iterated
                 * independently of the original object */
            }

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

        $collection[$foo] = 'Unfoo';
        unset($collection[$bar]);

        $this->assertTrue(isset($collection[$foo]));
        $this->assertFalse(isset($collection[$bar]));
        $this->assertTrue(isset($collection[$baz]));

        $this->assertSame('Unfoo', $collection[$foo]);
        $this->assertSame('Baz', $collection[$baz]);
    }
}
