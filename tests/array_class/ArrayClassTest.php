<?php

namespace alcamo\array_class;

use PHPUnit\Framework\TestCase;

class ArrayClassTest extends TestCase {
  public function testAll() {
    $data = [ 'foo', 'bar', 'baz' ];

    $a = new ArrayClass( $data );

    $this->assertSame( count( $a ), count( $data ) );

    $this->assertSame( $a->first(), 'foo' );

    $this->assertSame( $a->last(), 'baz' );

    $data2 = [];

    foreach ( $a as $key => $value ) {
      $data2[$value] = $key;
    }

    $data2 = array_flip( $data2 );

    $this->assertEquals( $data2, $data );

    $this->assertSame( $a[1], 'bar' );

    $this->assertTrue( isset( $a[2] ) );

    $this->assertFalse( isset( $a[3] ) );

    $this->assertFalse( isset( $a[3] ) );

    $a[3] = 'qux';

    $this->assertSame( count( $a ), count( $data ) + 1 );

    $this->assertSame( $a->first(), 'foo' );

    $this->assertSame( $a->last(), 'qux' );

    $this->assertSame( $a[3], 'qux' );

    unset( $a[0] );

    $this->assertNull( $a[0] );

    $this->assertSame( $a->first(), 'bar' );
  }
}
