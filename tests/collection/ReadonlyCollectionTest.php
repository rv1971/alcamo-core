<?php

namespace alcamo\collection;

use PHPUnit\Framework\TestCase;

use alcamo\exception\ReadonlyViolation;

class ReadonlyCollectionTest extends TestCase {
  public function testBasics() {
    $data = [ 'foo', 'bar', 'baz', 42, 'qux' ];

    $a = new ReadonlyCollection( $data );

    $this->assertSame( count( $data ), count( $a ) );

    $this->assertSame( 'foo', $a->first() );

    $this->assertSame( 'qux', $a->last() );

    $this->assertTrue( $a->contains( 42 ) );

    $this->assertFalse( $a->contains( '42' ) );

    $this->assertTrue( $a->contains( 'foo' ) );

    $data2 = [];

    foreach ( $a as $key => $value ) {
      $data2[$key] = $value;
    }

    $this->assertEquals( $data, $data2 );

    $this->assertSame( 'baz', $a[2] );

    $this->assertTrue( isset( $a[2] ) );

    $this->assertFalse( isset( $a[5] ) );

    $this->assertFalse( isset( $a[6] ) );

    $b = new ReadonlyCollection();

    $this->assertSame( 0, count( $b ) );

    $this->assertSame( null, $b->first() );

    $this->assertSame( null, $b->last() );
  }

  public function testUnset() {
    $a = new ReadonlyCollection( [ 'x' ] );

    $this->expectException( ReadonlyViolation::class );
    $this->expectExceptionMessage(
      'Attempt to modify readonly ' . ReadonlyCollection::class
        . ' object through offsetUnset()' );

    unset( $a[0] );
  }

  public function testSet() {
    $a = new ReadonlyCollection( [] );

    $this->expectException( ReadonlyViolation::class );
    $this->expectExceptionMessage(
      'Attempt to modify readonly ' . ReadonlyCollection::class
        . ' object through offsetSet()' );

    $a[0] = 1;
  }
}
