<?php

namespace alcamo\dom;

use PHPUnit\Framework\TestCase;
use alcamo\ietf\{Lang, Uri};
use alcamo\time\Duration;
use alcamo\xml\XName;

class AttrTest extends TestCase
{
    /**
     * @dataProvider conversionProvider
     */
    public function testConversion($attr, $method, $expectedResult)
    {
        switch ($method) {
            case 'toDateTime':
            case 'toDuration':
            case 'toLang':
            case 'toUri':
            case 'toXName':
                $this->assertEquals($expectedResult, $attr->$method());
                break;

            case 'curieToUri':
            case 'safeCurieToUri':
            case 'uriOrSafeCurieToUri':
                $this->assertSame($expectedResult, (string)$attr->$method());
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
            'datetime' => [
                $doc['datetime']->getAttributeNode('content'),
                'toDateTime',
                new \DateTime('2021-02-16T18:04:03.123+00:00')
            ],
            'duration' => [
                $doc['duration']->getAttributeNode('content'),
                'toDuration',
                new Duration('PT5M')
            ],
            'float' => [
                $doc['float']->getAttributeNode('content'),
                'toFloat',
                3.141
            ],
            'int' => [
                $doc->documentElement->getAttributeNode('barbaz'),
                'toInt',
                42
            ],
            'lang' => [
                $doc['lang']->getAttributeNode('content'),
                'toLang',
                new Lang('yo', 'NG')
            ],
            'longint' => [
                $doc['longint']->getAttributeNode('content'),
                'toInt',
                '123456789012345678901234567890'
            ],
            'curie' => [
                $doc['curie']->getAttributeNode('content'),
                'curieToUri',
                'http://purl.org/dc/terms/source'
            ],
            'safecurie' => [
                $doc['safecurie']->getAttributeNode('content'),
                'safeCurieToUri',
                'http://qux.example.org#1234'
            ],
            'uriorsafecurie1' => [
                $doc['uriorsafecurie1']->getAttributeNode('content'),
                'uriOrSafeCurieToUri',
                'http://www.example.biz/foo'
            ],
            'uriorsafecurie2' => [
                $doc['uriorsafecurie2']->getAttributeNode('content'),
                'uriOrSafeCurieToUri',
                'http://www.w3.org/2001/XMLSchema#token'
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
