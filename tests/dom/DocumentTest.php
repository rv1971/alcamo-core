<?php

namespace alcamo\dom;

use GuzzleHttp\Psr7\UriResolver;
use PHPUnit\Framework\TestCase;
use alcamo\exception\{AbsoluteUriNeeded, FileLoadFailed, Uninitialized};
use alcamo\ietf\Uri;

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
            (string)$doc->query('//rdfs:comment/text()')[0]
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
            'from-url' => [
                $doc1,
                (string)Uri::newFromFilesystemPath(
                    __DIR__ . DIRECTORY_SEPARATOR . 'foo.xml'
                )
            ],
            'from-xml' => [
                $doc2,
                (string)Uri::newFromFilesystemPath(
                    __DIR__ . DIRECTORY_SEPARATOR
                )
            ]
        ];
    }

    public function testXName()
    {
        $doc = Document::newFromUrl(
            __DIR__ . DIRECTORY_SEPARATOR . 'foo.xml'
        );

        $foo = $doc->documentElement;

        $this->assertSame(
            'http://foo.example.org foo',
            (string)$foo->getXName()
        );

        $this->assertSame(
            'qux',
            (string)$foo->getAttributeNode('qux')->getXName()
        );

        $this->assertSame(
            Document::NS['xml'] . ' lang',
            (string)$foo
                ->getAttributeNodeNS(Document::NS['xml'], 'lang')
                ->getXName()
        );
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
        $elem1 = (Document::newFromUrl(
            __DIR__ . DIRECTORY_SEPARATOR . 'foo.xml'
        ))->documentElement;

        $this->assertInstanceOf(Element::class, $elem1);

        /** If the Document object goes out of scope, it is destroyed, and the
         *  `$ownerDocument` property returns the underlying base object
         *  only. */
        $this->assertInstanceOf(\DOMDocument::class, $elem1->ownerDocument);

        $this->assertFalse($elem1->ownerDocument instanceof Document);

        $elem2 = (Document::newFromUrl(
            __DIR__ . DIRECTORY_SEPARATOR . 'foo.xml'
        ))->conserve()->documentElement;

        $this->assertInstanceOf(Element::class, $elem2);

        /** If the Document object is still referenced somewhere, the
         *  `$ownerDocument` property returns the complete derived object. */
        $this->assertInstanceOf(Document::class, $elem2->ownerDocument);

        $elem2->ownerDocument->unconserve();

        $this->assertInstanceOf(\DOMDocument::class, $elem2->ownerDocument);
        $this->assertFalse($elem2->ownerDocument instanceof Document);
    }

    public function testNoSchemaLocation()
    {
        $baz = Document::newFromUrl(
            __DIR__ . DIRECTORY_SEPARATOR . 'qux.xml'
        )->validate();

        $this->assertSame([], $baz->getSchemaLocations());
    }

    public function testNoNsValidate()
    {
        $bar = Document::newFromUrl(
            __DIR__ . DIRECTORY_SEPARATOR . 'bar.xml'
        )->validate();

        $this->assertEquals(
            UriResolver::resolve(
                new Uri($bar->documentURI),
                new Uri('bar.xsd')
            ),
            $bar->getSchemaLocations()[0]
        );

        $this->expectException(FileLoadFailed::class);

        $bar->validateWithSchema(__DIR__ . DIRECTORY_SEPARATOR . 'baz.xsd');
    }

    public function testValidate()
    {
        $bar = Document::newFromUrl(
            __DIR__ . DIRECTORY_SEPARATOR . 'foo.xml'
        )->validate();

        $this->assertEquals(
            [
                'http://foo.example.org',
                'http://www.w3.org/2000/01/rdf-schema#'
            ],
            array_keys($bar->getSchemaLocations())
        );
    }

    public function testNoNsValidateException()
    {
        ValidatedDocument::newFromUrl(
            __DIR__ . DIRECTORY_SEPARATOR . 'bar.xml'
        );

        $this->expectException(FileLoadFailed::class);

        ValidatedDocument::newFromUrl(
            __DIR__ . DIRECTORY_SEPARATOR . 'bar-invalid.xml'
        );
    }

    public function testValidateException()
    {
        ValidatedDocument::newFromUrl(
            __DIR__ . DIRECTORY_SEPARATOR . 'foo.xml'
        );

        $this->expectException(FileLoadFailed::class);

        ValidatedDocument::newFromUrl(
            __DIR__ . DIRECTORY_SEPARATOR . 'foo-invalid.xml'
        );
    }

    public function testCache()
    {
        $barUrl = 'file://' . __DIR__ . DIRECTORY_SEPARATOR . 'bar.xml';

        $bar = Document::newFromUrl($barUrl, true);

        $this->assertEquals($barUrl, $bar->documentURI);

        $bar->documentElement->setAttribute('foo', 'FOO');

        $this->assertSame('FOO', $bar->documentElement->getAttribute('foo'));

        // $bar2 does not use the cache, so it does not see the change to $bar
        $bar2Url = 'file://' . __DIR__ . DIRECTORY_SEPARATOR
            . 'extended' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR
            . 'bar.xml';

        $bar2 = Document::newFromUrl($bar2Url);

        $this->assertFalse($bar2->documentElement->hasAttribute('foo'));

        // $bar3 uses the cache, so so it sees the change
        $bar3Url = 'file://' . __DIR__ . DIRECTORY_SEPARATOR
            . 'xsd' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR
            . 'bar.xml';

        $bar3 = Document::newFromUrl($bar3Url, true);

        $this->assertSame($bar, $bar3);
        $this->assertSame('FOO', $bar3->documentElement->getAttribute('foo'));

        // adding $bar2 to the cache overwrites $bar
        $bar2->addToCache();

        $bar2->documentElement->setAttribute('baz', 'BAZ');

        // $bar4 uses the cache, so so it sees $bar2
        $bar4 = Document::newFromUrl($barUrl, true);

        $this->assertSame($bar4, $bar2);
        $this->assertFalse($bar4->documentElement->hasAttribute('foo'));
        $this->assertSame('BAZ', $bar4->documentElement->getAttribute('baz'));

        $bar2->removeFromCache();

        // $bar5 is loaded anew because the cache is empty
        $bar5 = Document::newFromUrl($barUrl, true);

        $this->assertFalse($bar5->documentElement->hasAttribute('foo'));
        $this->assertFalse($bar5->documentElement->hasAttribute('baz'));
    }

    public function testCacheException1()
    {
        $this->expectException(AbsoluteUriNeeded::class);
        $this->expectExceptionMessage(
            'Relative URI "bar.xml" given where absolute URI is needed'
        );

        $bar = Document::newFromUrl('bar.xml', true);
    }

    public function testCacheException2()
    {
        $barUrl = __DIR__ . DIRECTORY_SEPARATOR . 'bar.xml';

        $bar = Document::newFromUrl($barUrl);

        $this->expectException(AbsoluteUriNeeded::class);
        $this->expectExceptionMessage(
            "Relative URI \"$barUrl\" given where absolute URI is needed"
        );

        $bar->addToCache();
    }
}
