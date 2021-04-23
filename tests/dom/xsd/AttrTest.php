<?php

namespace alcamo\dom\xsd;

use PHPUnit\Framework\TestCase;
use alcamo\ietf\Uri;
use alcamo\xml\XName;

class AttrTest extends TestCase
{
    /**
     * @dataProvider getValueProvider
     */
    public function testGetValue($elem, $attrName, $expectedValue)
    {
        switch (explode('::', Attr::XSD_CONVERTERS[$attrName])[1]) {
            case 'toUri':
            case 'toXName':
            case 'toXNames':
                $this->assertEquals($expectedValue, $elem->$attrName);
                break;

            default:
                $this->assertSame($expectedValue, $elem->$attrName);
        }
    }

    public function getValueProvider()
    {
        $doc = Document::newFromUrl(
            dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR
            . 'xsd' . DIRECTORY_SEPARATOR . 'XMLSchema.xsd'
        )->conserve();

        return [
            'maxOccurs' => [
                $doc->query('//*[@maxOccurs = 1]')[0],
                'maxOccurs',
                1
            ],
            'maxOccurs-unbounded' => [
                $doc->query('//*[@maxOccurs = "unbounded"]')[0],
                'maxOccurs',
                -1
            ],
            'abstract' => [
                $doc->query('//*[@abstract = "true"]')[0],
                'abstract',
                true
            ],
            'mixed' => [
                $doc->query('//*[@mixed = "true"]')[0],
                'mixed',
                true
            ],
            'minOccurs' => [
                $doc->query('//*[@minOccurs = 0]')[0],
                'minOccurs',
                0
            ],
            'schemaLocation' => [
                $doc->query('//*[@schemaLocation = "xml.xsd"]')[0],
                'schemaLocation',
                new Uri('xml.xsd')
            ],
            'source' => [
                $doc->query('//*[@source = "http://www.w3.org/TR/xmlschema-1/#element-schema"]')[0],
                'source',
                new Uri('http://www.w3.org/TR/xmlschema-1/#element-schema')
            ],
            'system' => [
                $doc->query('//*[@system = "http://www.w3.org/2000/08/XMLSchema.xsd"]')[0],
                'system',
                new Uri('http://www.w3.org/2000/08/XMLSchema.xsd')
            ],
            'base' => [
                $doc->query('//*[@base = "xs:anyType"]')[0],
                'base',
                new Xname(Document::NS['xsd'], 'anyType')
            ],
            'itemType' => [
                $doc->query('//*[@itemType = "xs:reducedDerivationControl"]')[0],
                'itemType',
                new Xname(Document::NS['xsd'], 'reducedDerivationControl')
            ],
            'ref' => [
                $doc->query('//*[@ref = "xs:annotation"]')[0],
                'ref',
                new Xname(Document::NS['xsd'], 'annotation')
            ],
            'type' => [
                $doc->query('//*[@type = "xs:ID"]')[0],
                'type',
                new Xname(Document::NS['xsd'], 'ID')
            ],
            'memberTypes' => [
                $doc->query('//*[@memberTypes="xs:nonNegativeInteger"]')[0],
                'memberTypes',
                [ new Xname(Document::NS['xsd'], 'nonNegativeInteger') ]
            ],
        ];
    }

    public function testLangCache()
    {
        $doc = Document::newFromUrl(
            dirname(__DIR__) . DIRECTORY_SEPARATOR . 'foo.xml'
        )->conserve();

        $this->assertEquals('oc', (string)$doc->documentElement->getLang());

        $doc->documentElement
            ->setAttributeNS(Document::NS['xml'], 'xml:lang', 'cu');

        $this->assertEquals('cu', $doc->documentElement
            ->getAttributeNS(Document::NS['xml'], 'lang'));

        // language is cached and therefore does not see the change
        $this->assertEquals('oc', (string)$doc->documentElement->getLang());
    }

    /**
     * @dataProvider attrArrayAccessProvider
     */
    public function testAttrArrayAccess(
        $elem,
        $attrName,
        $expectedIsSet,
        $expectedValue
    ) {
        $this->assertSame($expectedIsSet, isset($elem->$attrName));
        $this->assertSame($expectedValue, $elem->$attrName);
    }

    public function attrArrayAccessProvider()
    {
        $doc = Document::newFromUrl(
            dirname(__DIR__) . DIRECTORY_SEPARATOR . 'foo.xml'
        )->conserve();

        return [
            'without-namespace' => [
                $doc->documentElement, 'qux', true, 'quux'
            ],
            'namespace-prefix' => [
                $doc->documentElement, 'xml:lang', true, 'oc'
            ],
            'xname' => [
                $doc->documentElement, Document::NS['xml'] . ' lang', true, 'oc'
            ],
            'unset-without-namespace' => [
                $doc->documentElement, 'barbarbar', false, null
            ],
            'unset-namespace-prefix' => [
                $doc->documentElement, 'dc:title', false, null
            ],
            'unset-xname' => [
                $doc->documentElement,
                Document::NS['rdfs'] . ' comment',
                false,
                null
            ]
        ];
    }
}
