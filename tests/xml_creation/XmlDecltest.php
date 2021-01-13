<?php

namespace alcamo\xml_creation;

use PHPUnit\Framework\TestCase;

use alcamo\exception\SyntaxError;

class XmlDeclTest extends TestCase {
  /**
   * @dataProvider allProvider
   */
  public function testAll(
    $version, $encoding, $standalone, $expectedString
  ) {
    $decl = new XmlDecl( $version, $encoding, $standalone );

    $this->assertNull( $decl->getContent() );
    $this->assertEquals( $decl->getVersion(), $version ?? '1.0' );
    $this->assertEquals( $decl->getEncoding(), $encoding ?? 'UTF-8' );
    $this->assertEquals( $decl->getStandalone(), $standalone ?? false );
    $this->assertEquals( (string)$decl, $expectedString );
  }

  public function allProvider() {
    return [
      [ null, null, null, '<?xml version="1.0" encoding="UTF-8"?>' ],
      [
        '1.1',
        'ISO-8859-1',
        true,
        '<?xml version="1.1" encoding="ISO-8859-1" standalone="yes"?>'
      ]
    ];
  }

  public function testVersionException() {
    $this->expectException( SyntaxError::class );
    $this->expectExceptionMessage(
      'Syntax error in "3.0"; not a valid XML version' );

    new XmlDecl( '3.0' );
  }

  public function testEncodingException() {
    $this->expectException( SyntaxError::class );
    $this->expectExceptionMessage(
      'Syntax error in "UTF/8"; not a valid XML encoding' );

    new XmlDecl( '1.2', 'UTF/8' );
  }
}
