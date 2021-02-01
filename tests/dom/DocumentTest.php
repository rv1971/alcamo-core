<?php

namespace alcamo\dom;

use PHPUnit\Framework\TestCase;
use alcamo\exception\Uninitialized;

class DocumentTest extends TestCase
{
    /**
     * @dataProvider contentProvider
     */
    public function testContent($doc, $expectedUri)
    {
        $this->assertInstanceOf(Element::class, $doc->documentElement);

        $this->assertInstanceOf(
            Attr::class,
            $doc->documentElement->getAttributeNode('qux')
        );

        $this->assertInstanceOf(
            Text::class,
            $doc->documentElement->firstChild->firstChild
        );

        $this->assertSame($expectedUri, $doc->documentURI);

        $this->assertSame(
            'quux',
            (string)$doc->documentElement->getAttributeNode('qux')
        );

        $this->assertSame(true, isset($doc['x']));

        $this->assertSame(false, isset($doc['xx']));

        $this->assertSame('bar', $doc['x']->tagName);

        $this->assertSame('At eos', (string)$doc['a']->firstChild);

        $this->assertSame('baz', $doc->query('//foo:baz')[0]->tagName);

        $this->assertSame(
            'Lorem ipsum',
            (string)$doc->query('//dc:title/text()')[0]
        );

        $this->assertSame(4, (int)$doc->evaluate('count(//foo:baz)'));

        $this->assertInstanceOf(
            \XSLTProcessor::class,
            $doc->getXsltProcessor()
        );
    }

    public function contentProvider()
    {
        $doc1 =
            Document::newFromUrl(__DIR__ . DIRECTORY_SEPARATOR . 'foo.xml');

        $doc1->getXPath()->registerNamespace('foo', 'http://foo.example.org');

        chdir(__DIR__);

        $doc2 = Document::newFromXmlText(
            file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'foo.xml')
        );

        $doc2->getXPath()->registerNamespace('foo', 'http://foo.example.org');

        return [
            'from-url' => [ $doc1, __DIR__ . DIRECTORY_SEPARATOR . 'foo.xml' ],
            'from-xml' => [ $doc2, __DIR__ . DIRECTORY_SEPARATOR ]
        ];
    }

    public function testXPathException()
    {
        $doc = new Document();

        $this->expectException(Uninitialized::class);
        $this->expectExceptionMessage(
            'Accessing uninitialized ' . Document::class
        );

        $doc->getXPath();
    }

    public function testXsltProcessorException()
    {
        $doc = new Document();

        $this->expectException(Uninitialized::class);
        $this->expectExceptionMessage(
            'Accessing uninitialized ' . Document::class
        );

        $doc->getXsltProcessor();
    }

    public function testScope()
    {
        $elem1 = $this->scopeAux1();

        $this->assertInstanceOf(Element::class, $elem1);

        /** If the Document object goes out of scope, it is destroyed, and the
         *  `$ownerDocument` property returns the underlying base object
         *  only. */
        $this->assertInstanceOf(\DOMDocument::class, $elem1->ownerDocument);

        $this->assertFalse($elem1->ownerDocument instanceof Document);

        $elem2 = $this->scopeAux2();

        $this->assertInstanceOf(Element::class, $elem2);

        /** If the Document object is still referenced somewhere, the
         *  `$ownerDocument` property returns the complete derived object. */
        $this->assertInstanceOf(Document::class, $elem2->ownerDocument);

        $elem2->ownerDocument->unconserve();

        $this->assertInstanceOf(\DOMDocument::class, $elem2->ownerDocument);
        $this->assertFalse($elem2->ownerDocument instanceof Document);
    }

    public function scopeAux1()
    {
        $doc = Document::newFromUrl(__DIR__ . DIRECTORY_SEPARATOR . 'foo.xml');

        return $doc->documentElement;
    }

    public function scopeAux2()
    {
        $scopeAux2Doc =
            Document::newFromUrl(__DIR__ . DIRECTORY_SEPARATOR . 'foo.xml');

        $scopeAux2Doc->conserve();

        return $scopeAux2Doc->documentElement;
    }
}
