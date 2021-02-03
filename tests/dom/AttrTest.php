<?php

namespace alcamo\dom;

use PHPUnit\Framework\TestCase;
use alcamo\ietf\Uri;
use alcamo\xml\XName;

class AttrTest extends TestCase
{
    /**
     * @dataProvider conversionProvider
     */
    public function testConversion($attr, $method, $expectedResult)
    {
        switch ($method) {
            case 'toUri':
            case 'toXName':
                $this->assertEquals($expectedResult, $attr->$method());
                break;

            default:
                $this->assertSame($expectedResult, $attr->$method());
        }
    }

    public function conversionProvider()
    {
        $doc = Document::newFromUrl(
            __DIR__ . DIRECTORY_SEPARATOR . 'foo.xml'
        )->conserve();

        return [
            'array' => [
                $doc->documentElement->getAttributeNode('foobar'),
                'toArray',
                [ 'foo', 'bar', 'baz' ]
            ],
            'bool-true' => [
                $doc->documentElement->getAttributeNode('bar'),
                'toBool',
                true
            ],
            'bool-false' => [
                $doc->documentElement->getAttributeNode('baz'),
                'toBool',
                false
            ],
            'int' => [
                $doc->documentElement->getAttributeNode('barbaz'),
                'toInt',
                42
            ],
            'uri' => [
                $doc->documentElement
                    ->getAttributeNodeNS(Document::NS['dc'], 'source'),
                'toUri',
                new Uri('http://www.example.org/foo')
            ],
            'XName' => [
                $doc->documentElement->getAttributeNode('bazbaz'),
                'toXName',
                new XName(Document::NS['dc'], 'title')
            ]
        ];
    }
}
