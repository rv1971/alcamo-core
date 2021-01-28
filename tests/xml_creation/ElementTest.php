<?php

namespace alcamo\xml_creation;

use Ds\Map;
use PHPUnit\Framework\TestCase;
use alcamo\collection\Collection;
use alcamo\exception\SyntaxError;

class ElementTest extends TestCase
{
  /**
   * @dataProvider basicsProvider
   */
    public function testBasics($tagName, $attrs, $content, $expectedString)
    {
        $attr = new Element($tagName, $attrs, $content);

        $this->assertSame($tagName, $attr->getTagName());

        $this->assertSame(
            $attrs instanceof Map ?  $attrs->toArray() : (array)$attrs,
            $attr->getAttrs()
        );

        $this->assertSame($content, $attr->getContent());

        $this->assertEquals($expectedString, (string)$attr);
    }

    public function basicsProvider()
    {
        return [
        'empty-tag' => [
        'foo', null, null, '<foo/>'
        ],
        'empty-tag-with-attrs' => [
        'bar',
        [ 'baz' => '<<<qux>>>', 'QUUX' => [ 1, 2, 3 ] ],
        null,
        '<bar baz="&lt;&lt;&lt;qux&gt;&gt;&gt;" QUUX="1 2 3"/>'
        ],
        'tag-with-text-content' => [
        'baz',
        null,
        'Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.',
        '<baz>Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</baz>'
        ],
        'tag-with-text-content-and-attrs' => [
        'qux',
        [ 'xml:id' => 'QUX', 'xml:lang' => 'oc' ],
        'Coordinacion de totes los projèctes',
        '<qux xml:id="QUX" xml:lang="oc">Coordinacion de totes los projèctes</qux>'
        ],
        'tag-with-array-content-and-attrs' => [
        'ns42:quux',
        [ 'rdf:ID' => 'element-42' ],
        [ 'Lorem ', new Element('xh:b', null, 'ipsum'), ' dolor sit amet' ],
        '<ns42:quux rdf:ID="element-42">Lorem <xh:b>ipsum</xh:b> dolor sit amet</ns42:quux>'
        ],
        'tag-with-complex-content-and-attr-object' => [
        'body',
        new Map([
          'xmlns' => 'http://www.w3.org/1999/xhtml',
          'class' => 'overview'
        ]),
        new Element(
            'div',
            [ 'class' => 'main' ],
            new Collection([
            'Stet clita kasd gubergren, ',
            new Element('i', null, 'no sea takimata'),
            '.'
            ])
        ),
        '<body xmlns="http://www.w3.org/1999/xhtml" class="overview">'
        . '<div class="main">Stet clita kasd gubergren, <i>no sea takimata</i>.</div></body>'
        ]
        ];
    }

    public function testTagNameException()
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage(
            'Syntax error in ".qux"; not a valid XML tag name'
        );

        new Element('.qux');
    }

    public function testAttrNameException()
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage(
            'Syntax error in "424242"; not a valid XML attribute name'
        );

        try {
            new Element('quux', [ '424242' => 'bar' ]);
        } catch (SyntaxError $e) {
            $this->assertSame('quux', $e->tagName);

            throw $e;
        }
    }
}
