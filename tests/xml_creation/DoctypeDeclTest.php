<?php

namespace alcamo\xml_creation;

use PHPUnit\Framework\TestCase;
use alcamo\exception\SyntaxError;

class DoctypeDeclTest extends TestCase
{
  /**
   * @dataProvider basicsProvider
   */
    public function testBasics(
        $name,
        $externalId,
        $intSubset,
        $expectedString
    ) {
        $decl = new DoctypeDecl($name, $externalId, $intSubset);

        $this->assertSame($name, $decl->getName());
        $this->assertSame($externalId, $decl->getExternalId());
        $this->assertSame($intSubset, $decl->getContent());
        $this->assertEquals($expectedString, (string)$decl);
    }

    public function basicsProvider()
    {
        return [
        [ 'html', null, null, '<!DOCTYPE html>' ],
        [
        'xs:schema',
        'PUBLIC "-//W3C//DTD XMLSCHEMA 200102//EN" "XMLSchema.dtd"',
        '<!ATTLIST xs:schema id ID #IMPLIED>',
        '<!DOCTYPE xs:schema PUBLIC "-//W3C//DTD XMLSCHEMA 200102//EN" '
        . '"XMLSchema.dtd" [ <!ATTLIST xs:schema id ID #IMPLIED> ]>'
        ]
        ];
    }

    public function testException()
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage(
            'Syntax error in "-foo-"; not a valid XML doctype name'
        );

        new DoctypeDecl('-foo-');
    }
}
