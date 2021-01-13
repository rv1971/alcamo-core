<?php

namespace alcamo\xml_creation;

use PHPUnit\Framework\TestCase;

use alcamo\array_class\ArrayClass;
use alcamo\exception\SyntaxError;

class ElementTest extends TestCase {
  /**
   * @dataProvider allProvider
   */
  public function testAll( $tagName, $attrs, $content, $expectedString ) {
    $attr = new Element( $tagName, $attrs, $content );

    $this->assertSame( $attr->getTagName(), $tagName );

    $this->assertSame( $attr->getAttrs(), (array)$attrs );

    $this->assertSame( $attr->getContent(), $content );

    $this->assertEquals( (string)$attr, $expectedString );
  }

  public function allProvider() {
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
        [ 'Lorem ', new Element( 'xh:b', null, 'ipsum' ), ' dolor sit amet' ],
        '<ns42:quux rdf:ID="element-42">Lorem <xh:b>ipsum</xh:b> dolor sit amet</ns42:quux>'
      ],
      'tag-with-complex-content-and-attrs' => [
        'body',
        [ 'xmlns' => 'http://www.w3.org/1999/xhtml', 'class' => 'overview' ],
        new Element(
          'div',
          [ 'class' => 'main' ],
          new ArrayClass( [
            'Stet clita kasd gubergren, ',
            new Element( 'i', null, 'no sea takimata' ),
            '.'
          ] )
        ),
        '<body xmlns="http://www.w3.org/1999/xhtml" class="overview"><div class="main">Stet clita kasd gubergren, <i>no sea takimata</i>.</div></body>'
      ]
    ];
  }

  public function testTagNameException() {
    $this->expectException( SyntaxError::class );
    $this->expectExceptionMessage(
      'Syntax error in ".qux"; not a valid XML tag name' );

    new Element( '.qux' );
  }

  public function testAttrNameException() {
    $this->expectException( SyntaxError::class );
    $this->expectExceptionMessage(
      'Syntax error in "424242"; not a valid XML attribute name' );

    try {
      new Element( 'quux', [ '424242' => 'bar' ] );
    } catch ( SyntaxError $e ) {
      $this->assertSame( $e->tagName, 'quux' );

      throw $e;
    }
  }
}
