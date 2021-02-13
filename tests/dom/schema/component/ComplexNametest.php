<?php

namespace alcamo\dom\schema\component;

use PHPUnit\Framework\TestCase;
use alcamo\dom\extended\Document;
use alcamo\dom\schema\Schema;
use alcamo\xml\XName;

class ComplexNameTest extends TestCase
{

    /**
     * @dataProvider getElementDeclsProvider
     */
    /*
    public function testGetElementDecls($group, $expectedElementLocalNames)
    {
        $decls = $group->getElementDecls();

        $this->assertSame(count($expectedElementLocalNames), count($decls));

        $i = 0;
        foreach ($decls as $decl) {
            $this->assertSame($expectedElementLocalNames[$i++], $decl['name']);
        }
    }

    public function getElementDeclsProvider()
    {
        $doc = Document::newFromUrl(
            dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR
            . 'xsd' . DIRECTORY_SEPARATOR . 'XMLSchema.xsd'
        )->conserve();

        return [
            'complexContent' => [
                $doc['complexContent']->query('xsd:complexType')[0],
                [ 'restriction', 'extension' ]
            ],
            'simpleRestrictionType' => [
                $doc->query('xsd:complexType[@name="simpleRestrictionType"]')[0],
                [
                    'annotation',
                    'simpleType',
                    'minExclusive',
                    'minInclusive',
                    'maxExclusive',
                    'maxInclusive',
                    'totalDigits',
                    'fractionDigits',
                    'length',
                    'minLength',
                    'maxLength',
                    'enumeration',
                    'whiteSpace',
                    'pattern',
                    'attribute',
                    'attributeGroup',
                    'anyAttribute'
                ]
            ]
        ];
    }
    */
}
