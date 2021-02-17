<?php

namespace alcamo\dom;

use PHPUnit\Framework\TestCase;
use alcamo\ietf\{Lang, Uri};
use alcamo\time\Duration;
use alcamo\xml\XName;

class ConverterPoolTest extends TestCase
{
    /**
     * @dataProvider conversionProvider
     */
    public function testConversion($attr, $converter, $expectedResult)
    {
        $qualifiedConverter = ConverterPool::class . "::$converter";

        switch ($converter) {
            case 'toDateTime':
            case 'toDuration':
            case 'toLang':
            case 'toUri':
            case 'toXName':
                $this->assertEquals(
                    $expectedResult,
                    $qualifiedConverter($attr->value, $attr)
                );
                break;

            case 'base64ToBinary':
            case 'hexToBinary':
            case 'curieToUri':
            case 'safeCurieToUri':
            case 'uriOrSafeCurieToUri':
                $this->assertSame(
                    $expectedResult,
                    (string)$qualifiedConverter($attr->value, $attr)
                );
                break;

            default:
                $this->assertSame(
                    $expectedResult,
                    $qualifiedConverter($attr->value, $attr)
                );
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
            'base64' => [
                $doc['base64']->getAttributeNode('content'),
                'base64ToBinary',
                '666F6F0A'
            ],
            'hex' => [
                $doc['hex']->getAttributeNode('content'),
                'hexToBinary',
                '1234ABCDEF'
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
            'safecurie2' => [
                $doc['corge']->getAttributeNode('safecurie'),
                'safeCurieToUri',
                'http://foo.example.org/bar?baz=qux'
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
