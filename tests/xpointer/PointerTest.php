<?php

namespace alcamo\xpointer;

use PHPUnit\Framework\TestCase;

class PointerTest extends TestCase
{
    /**
     * @dataProvider basicsProvider
     */
    public function testBasics(
        $doc,
        $pointerString,
        $expectedLocalName,
        $expectedContent
    ) {
        $pointer = Pointer::newFromString($pointerString);

        $result = $pointer->process($doc);

        if ($result instanceof \DOMNode) {
            $this->assertSame($expectedLocalName, $result->localName);
            $this->assertSame($expectedContent, $result->textContent);
        } else {
            $this->assertSame($expectedLocalName, $result[0]->localName);
            $this->assertSame($expectedContent, $result[0]->textContent);
        }
    }

    public function basicsProvider()
    {
        $doc = new \DOMDocument();

        $doc->load(__DIR__ . DIRECTORY_SEPARATOR . 'foo.xml');

        return [
            'shorthand' => [ $doc, 'quux42', 'quux', 'consetetur' ],

            'xpointer' => [
                $doc,
                'xpointer(/*/*[2]/@content)',
                'content',
                'qux'
            ],

            'xmlns-xpointer' => [
                $doc,
                'xmlns(f=http://foo.example.org) xpointer(//f:baz)',
                'baz',
                'Lorem ipsum'
            ],

            'xmlns-xpointer-xmlns-xpointer' => [
                $doc,
                'xmlns(f=http://foo.example.org)'
                . 'xpointer(//f:bazzz)'
                . 'xmlns(b=http://bar.example.com)'
                . 'xpointer(//b:quux)',
                'quux',
                'consetetur'
            ]
        ];
    }
}
