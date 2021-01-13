<?php

namespace alcamo\exception;

use PHPUnit\Framework\TestCase;

class InvalidTypeTest extends TestCase {
  /**
   * @dataProvider constructProvider
   */
  public function testConstruct(
    $value, $validTypes, $message, $code, $expectedMessage
  ) {
    $e = new InvalidType( $value, $validTypes, $message, $code );

    $this->assertSame( $value, $e->value );

    $this->assertSame( $validTypes, $e->validTypes );

    $this->assertSame( $expectedMessage, $e->getMessage() );

    $this->assertEquals( $code, $e->getCode() );
  }

  public function constructProvider() : array {
    return [
      'typical-use' => [
        $this,
        [ 'string', 'integer' ],
        null,
        null,
        "Invalid type 'alcamo\\exception\\InvalidTypeTest', expected one of: 'string', 'integer'"
      ],

      'custom-message' => [
        new SyntaxError( 'foo' ),
        [ self::class ],
        'At vero eos et accusam',
        7,
        'At vero eos et accusam'
      ],

      'extra-message' => [
        42,
        [ 'string' ],
        '; at vero eos et accusam',
        8,
        "Invalid type 'integer', expected one of: 'string'; at vero eos et accusam"
      ]
    ];
  }
}
