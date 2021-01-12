<?php

namespace alcamo\exception;

use PHPUnit\Framework\TestCase;

class InvalidEnumeratorTest extends TestCase {
  /**
   * @dataProvider constructProvider
   */
  public function testConstruct(
    $value, $validValues, $message, $code, $expectedMessage
  ) {
    $e = new InvalidEnumerator( $value, $validValues, $message, $code );

    $this->assertSame( $e->value, $value );

    $this->assertSame( $e->validValues, $validValues );

    $this->assertSame( $e->getMessage(), $expectedMessage );

    $this->assertEquals( $e->getCode(), $code );
  }

  public function constructProvider() : array {
    return [
      'typical-use' => [
        'foo',
        [ 'bar', 'baz' ],
        null,
        null,
        "Invalid value 'foo', expected one of: 'bar', 'baz'"
      ],

      'custom-message' => [
        'baz',
        [ 'FOO' ],
        'At vero eos et accusam',
        43,
        'At vero eos et accusam'
      ],

      'extra-message' => [
        'qux',
        [ 'quux', 'bar', 'baz' ],
        '; at vero eos et accusam',
        44,
        "Invalid value 'qux', expected one of: 'quux', 'bar', 'baz'; at vero eos et accusam"
      ]
    ];
  }
}
