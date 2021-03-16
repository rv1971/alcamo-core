<?php

namespace alcamo\xpointer;

use PHPUnit\Framework\TestCase;

class XmlnsPartTest extends TestCase
{
    public function testConstruct()
    {
        $pointer = Pointer::newFromString('foo');

        $this->assertSame(
            [ 'xml' => 'http://www.w3.org/XML/1998/namespace' ],
            $pointer->getNsBindings()
        );

        new XmlnsPart($pointer, 'foo=http://foo.example.org');

        $this->assertSame(
            [
                'xml' => 'http://www.w3.org/XML/1998/namespace',
                'foo' => 'http://foo.example.org'
            ],
            $pointer->getNsBindings()
        );

        new XmlnsPart($pointer, 'bar=http://bar.example.info');

        $this->assertSame(
            [
                'xml' => 'http://www.w3.org/XML/1998/namespace',
                'foo' => 'http://foo.example.org',
                'bar' => 'http://bar.example.info'
            ],
            $pointer->getNsBindings()
        );

        new XmlnsPart($pointer, 'foo=http://foo.example.com');

        $this->assertSame(
            [
                'xml' => 'http://www.w3.org/XML/1998/namespace',
                'foo' => 'http://foo.example.com',
                'bar' => 'http://bar.example.info'
            ],
            $pointer->getNsBindings()
        );
    }
}
