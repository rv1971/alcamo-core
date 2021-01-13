<?php

namespace alcamo\xml_creation;

use PHPUnit\Framework\TestCase;

use alcamo\exception\SyntaxError;

class DoctypeDeclTest extends TestCase {
  /**
   * @dataProvider allProvider
   */
  public function testAll( $name, $externalId, $intSubset, $expectedString ) {
    $decl = new DoctypeDecl( $name, $externalId, $intSubset );

    $this->assertSame( $decl->getName(), $name );
    $this->assertSame( $decl->getExternalId(), $externalId );
    $this->assertSame( $decl->getContent(), $intSubset );
    $this->assertEquals( (string)$decl, $expectedString );
  }

  public function allProvider() {
    return [
      [ 'html', null, null, '<!DOCTYPE html>' ],
      [
        'xs:schema',
        'PUBLIC "-//W3C//DTD XMLSCHEMA 200102//EN" "XMLSchema.dtd"',
        '<!ATTLIST xs:schema id ID #IMPLIED>',
        '<!DOCTYPE xs:schema PUBLIC "-//W3C//DTD XMLSCHEMA 200102//EN" "XMLSchema.dtd" [ <!ATTLIST xs:schema id ID #IMPLIED> ]>'
      ]
    ];
  }

  public function testException() {
    $this->expectException( SyntaxError::class );
    $this->expectExceptionMessage(
      'Syntax error in "-foo-"; not a valid XML doctype name' );

    new DoctypeDecl( '-foo-' );
  }
}
