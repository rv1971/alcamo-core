<?php

namespace alcamo\xml;

use PHPUnit\Framework\TestCase;
use alcamo\xml\exception\UnknownNamespacePrefix;

class XNameTest extends TestCase
{
    /**
     * @dataProvider newFromQNameAndMapProvider
     */
    public function testNewFromQNameAndMap(
        $qName,
        $map,
        $defaultNs,
        $expectedNsName,
        $expectedLocalName
    ) {
        $xName = XName::newFromQNameAndMap($qName, $map, $defaultNs);

        $this->assertSame($expectedNsName, $xName->getNsName());

        $this->assertSame($expectedLocalName, $xName->getLocalName());
    }

    public function newFromQNameAndMapProvider(): array
    {
        return [
            'no-ns' => [
                'foo',
                [],
                null,
                null,
                'foo'
            ],
            'with-ns' => [
                'foo:bar',
                [ 'foo' => 'http://foo.example.org' ],
                null,
                'http://foo.example.org',
                'bar'
            ],
            'default-ns' => [
                'qux',
                [ 'foo' => 'http://foo.example.org' ],
                'http://www.example.com',
                'http://www.example.com',
                'qux'
            ]
        ];
    }

    public function newFromQNameAndMapExceptionTest()
    {
        $this->expectException(UnknownNamespacePrefix::class);
        $this->expectExceptionMessage(
            'Unknown namespace prefix "quux", expecting one of: "baz"'
        );

        XName::newFromQNameAndMap(
            'quux:foo',
            [ 'baz' => 'http:/baz.example.info' ]
        );
    }

    /**
     * @dataProvider newFromQNameAndContextProvider
     */
    public function testNewFromQNameAndContext(
        $qName,
        $context,
        $defaultNs,
        $expectedNsName,
        $expectedLocalName
    ) {
        $xName = XName::newFromQNameAndContext($qName, $context, $defaultNs);

        $this->assertSame($expectedNsName, $xName->getNsName());

        $this->assertSame($expectedLocalName, $xName->getLocalName());
    }

    public function newFromQNameAndContextProvider(): array
    {
        $doc = new \DOMDocument();

        $doc->loadXml(
            '<?xml version="1.0" encoding="utf-8"?>'
            . '<foo xmlns="https://foo.example.edu" xmlns:bar="https://bar.example.org"/>'
        );

        $doc2 = new \DOMDocument();

        $doc2->loadXml('<?xml version="1.0" encoding="utf-8"?><bar/>');

        return [
            'with-ns' => [
                'bar:baz',
                $doc->documentElement,
                null,
                'https://bar.example.org',
                'baz'
            ],
            'default-ns-from-context' => [
                'qux',
                $doc->documentElement,
                null,
                'https://foo.example.edu',
                'qux'
            ],
            'explicit-default-ns' => [
                'quux',
                $doc->documentElement,
                'https://quuz.example.com',
                'https://quuz.example.com',
                'quux'
            ],
            'empty-default-ns-from-context' => [
                'corge',
                $doc2,
                null,
                null,
                'corge'
            ]
        ];
    }

    public function newFromQNameAndContextExceptionTest()
    {
        $doc = new \DOMDocument();

        $doc->loadXml('<?xml version="1.0" encoding="utf-8"?><foo/>');

        $this->expectException(UnknownNamespacePrefix::class);
        $this->expectExceptionMessage(
            'Unknown namespace prefix "qux"'
        );

        XName::newFromQNameAndContext('qux:foo', $doc);
    }

    /**
     * @dataProvider newFromUriProvider
     */
    public function testNewFromUri(
        $uri,
        $defaultNs,
        $expectedNsName,
        $expectedLocalName
    ) {
        $xName = XName::newFromUri($uri, $defaultNs);

        $this->assertSame($expectedNsName, $xName->getNsName());

        $this->assertSame($expectedLocalName, $xName->getLocalName());
    }

    public function newFromUriProvider()
    {
        return [
            [ 'http://example.com/_foo', null, 'http://example.com/', '_foo' ],
            [ 'http://example.biz#bar', null, 'http://example.biz#', 'bar' ],
            [ 'https://example.biz?4baz', null, 'https://example.biz?4', 'baz' ],
            [ 'http://example.com/', null, 'http://example.com/', '' ],
            [ 'foo', null, '', 'foo' ],
            [ 'foo', 'https://example.info#', 'https://example.info#', 'foo' ],
            [ '', null, '', '' ],
            [ '', 'https://example.org?', 'https://example.org?', '' ]
        ];
    }
}
