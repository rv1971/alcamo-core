<?php

namespace alcamo\html_creation\element;

use PHPUnit\Framework\TestCase;
use alcamo\xml_creation\TokenList;

class ATest extends TestCase
{
  /**
   * @dataProvider basicsProvider
   */
    public function testBasics(
        $href,
        $content,
        $attrs,
        $expectedString
    ) {
        $a = A::newFromUrl($href, $content, $attrs);

        $this->assertSame('a', $a->getTagName());

        $this->assertInstanceOf(TokenList::class, $a['class']);

        $this->assertSame($href, $a['href']);

        $this->assertSame($content ?? $href, $a->getContent());

        $this->assertEquals($expectedString, (string)$a);
    }

    public function basicsProvider()
    {
        return [
        'typical-use' => [
        'qux.html',
        null,
        null,
        '<a href="qux.html">qux.html</a>'
        ],

        'with-content' => [
        'quux.php',
        'QUUX',
        [ 'rel' => 'dc:isPartOf' ],
        '<a href="quux.php" rel="dc:isPartOf">QUUX</a>'
        ]
        ];
    }
}
