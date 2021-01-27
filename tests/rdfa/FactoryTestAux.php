<?php

namespace alcamo\rdfa;

use PHPUnit\Framework\TestCase;

class FactoryTestAux extends TestCase {
  public function testData( $data, $expectedData ) {
    $i = 0;
    foreach ( $data as $key => $item ) {
      $expectedItem = $expectedData[$i++];

      $this->assertSame( $expectedItem['key'], $key );

      if ( is_array( $item ) ) {
        $j = 0;
        foreach ( $item as $subitem ) {
          $this->testItem_( $subitem, $expectedItem[$j++] );
        }
      } else {
        $this->testItem_( $item, $expectedItem );
      }
    }
  }

  private function testItem_( $item, $expectedItem ) {
    $expectedItemClass = $expectedItem['class'];

    $this->assertSame( $expectedItem['property'], $item->getProperty() );
    $this->assertInstanceOf( $expectedItemClass, $item );

    if ( defined( "$expectedItemClass::OBJECT_CLASS" ) ) {
      $this->assertInstanceOf(
        $expectedItemClass::OBJECT_CLASS, $item->getObject()
      );
    }

    $this->assertSame( $expectedItem['isResource'], $item->isResource() );

    $this->assertSame( $expectedItem['string'], (string)$item );

    $this->assertSame( $expectedItem['xmlAttrs'], $item->toXmlAttrs() );

    $this->assertSame( $expectedItem['html'], (string)$item->toHtmlNodes() );

    $this->assertSame( $expectedItem['httpHeaders'], $item->toHttpHeaders() );
  }
}
