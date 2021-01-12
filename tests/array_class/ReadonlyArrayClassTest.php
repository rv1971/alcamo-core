<?php

namespace alcamo\array_class;

use PHPUnit\Framework\TestCase;

use alcamo\exception\ReadonlyViolation;

class ReadonlyArrayClassTest extends TestCase {
  public function testAll() {
    $data = [ 'foo', 'bar', 'baz', 'qux' ];

    $a = new ReadonlyArrayClass( $data );

    $this->assertSame( count( $a ), count( $data ) );

    $this->assertSame( $a->first(), 'foo' );

    $this->assertSame( $a->last(), 'qux' );

    $data2 = [];

    foreach ( $a as $key => $value ) {
      $data2[$key] = $value;
    }

    $this->assertEquals( $data2, $data );

    $this->assertSame( $a[2], 'baz' );

    $this->assertTrue( isset( $a[2] ) );

    $this->assertFalse( isset( $a[4] ) );

    $this->assertFalse( isset( $a[5] ) );
  }

  public function testUnset() {
    $a = new ReadonlyArrayClass( [ 'x' ] );

    $this->expectException( ReadonlyViolation::class );
    $this->expectExceptionMessage(
      'Attempt to modify readonly ' . ReadonlyArrayClass::class
        . ' object through offsetUnset()' );

    unset( $a[0] );
  }

  public function testSet() {
    $a = new ReadonlyArrayClass( [] );

    $this->expectException( ReadonlyViolation::class );
    $this->expectExceptionMessage(
      'Attempt to modify readonly ' . ReadonlyArrayClass::class
        . ' object through offsetSet()' );

    $a[0] = 1;
  }
}
