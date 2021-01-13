<?php

namespace alcamo\exception;

use PHPUnit\Framework\TestCase;

class FileNotFoundTest extends TestCase {
  /**
   * @dataProvider constructProvider
   */
  public function testConstruct(
    $filename, $places, $message, $code, $expectedMessage
  ) {
    $e = new FileNotFound( $filename, $places, $message, $code );

    $this->assertSame( $filename, $e->filename );

    $this->assertSame( $places, $e->places );

    $this->assertSame( $expectedMessage, $e->getMessage() );

    $this->assertEquals( $code, $e->getCode() );
  }

  public function constructProvider() : array {
    $path1 = '/usr/local/share';
    $path2 = '/usr/share';

    return [
      'typical-use' => [
        'foo.txt',
        "$path1:$path2",
        null,
        null,
        "File 'foo.txt' not found in '$path1:$path2'"
      ],

      'no-path' => [
        'BAR',
        null,
        null,
        null,
        "File 'BAR' not found"
      ],

      'custom-message' => [
        'baz.json',
        'remote disk',
        'Lorem ipsum',
        42,
        'Lorem ipsum'
      ],

      'extra-message' => [
        'qux.xml',
        'DVD',
        '; stet clita kasd gubergren',
        43,
        "File 'qux.xml' not found in 'DVD'; stet clita kasd gubergren"
      ]
    ];
  }
}
