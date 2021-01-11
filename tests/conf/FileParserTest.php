<?php

namespace alcamo\conf;

use PHPUnit\Framework\TestCase;

use alcamo\exception\InvalidEnumerator;

class FileParserTest extends TestCase {
  public function testParseIni() {
    $parser = new FileParser();

    $iniFilename = dirname( __DIR__ ) . DIRECTORY_SEPARATOR .
      'alcamo' . DIRECTORY_SEPARATOR . 'bar.ini';

    $iniData = $parser->parse( $iniFilename );

    $this->assertSame(
      $iniData,
      [
        'quux' => 45,
        'corge' => 'foo bar baz',
        'bar' => 46
      ]
    );
  }

  public function testParseJson() {
    $parser = new FileParser();

    $jsonFileName = dirname( __DIR__ ) . DIRECTORY_SEPARATOR .
      'alcamo' . DIRECTORY_SEPARATOR . 'foo.json';

    $jsonData = $parser->parse( $jsonFileName );

    $this->assertSame(
      $jsonData,
      [
        'bar' => 44,
        'baz' => 'Stet clita kasd gubergren',
        'qux' => true
        ]
    );
  }

  public function testInvalidExtension() {
    $txtFileName = dirname( __DIR__ ) . DIRECTORY_SEPARATOR .
      'alcamo' . DIRECTORY_SEPARATOR . 'baz.txt';

    $this->expectException( InvalidEnumerator::class );
    $this->expectExceptionMessage(
      "Invalid file extension in '$txtFileName', expected one of: 'ini', 'json'" );

    $parser = new FileParser();

    $parser->parse( $txtFileName );
  }
}
