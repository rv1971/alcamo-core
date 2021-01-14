<?php

namespace alcamo\html_creation\element;

use PHPUnit\Framework\TestCase;

use alcamo\xml_creation\TokenList;

class LinkTest extends TestCase {
  /**
   * @dataProvider allProvider
   */
  public function testAll(
    $rel, $href, $attrs, $expectedString
  ) {
    $link = new Link( $rel, $href, $attrs );

    $this->assertSame( 'link', $link->getTagName() );

    $this->assertInstanceOf( TokenList::class, $link['class'] );

    $this->assertSame( $rel ?? $attrs['rel'], $link['rel'] );

    $this->assertSame( $href ?? $attrs['href'], $link['href'] );

    $this->assertNull( $link->getContent() );

    $this->assertEquals( $expectedString, (string)$link );
  }

  public function allProvider() {
    return [
      'typical-use' => [
        'stylesheet',
        'foo.css',
        [ 'type' => 'text/css' ],
        '<link rel="stylesheet" href="foo.css" type="text/css"/>'
      ],

      'empty-args' => [
        null,
        null,
        [ 'href' => 'bar.html', 'rel' => 'dc:isVersionOf' ],
        '<link rel="dc:isVersionOf" href="bar.html"/>'
      ],

      'override-attrs' => [
        'dc:isVersionOf',
        'baz.php',
        [ 'href' => 'qux.php', 'rel' => 'dc:source' ],
        '<link rel="dc:isVersionOf" href="baz.php"/>'
      ],

      'override-rel' => [
        'dc:isVersionOf',
        null,
        [ 'href' => 'qux.php', 'rel' => 'dc:source' ],
        '<link rel="dc:isVersionOf" href="qux.php"/>'
      ]
    ];
  }

  public function testMissingHref() {
    $this->expectError();

    $this->expectErrorMessage( 'Undefined index: href' );

    new Link( 'owl:sameAs', null, [ 'id' => 'foo' ] );
  }

  public function testMissingHref2() {
    $this->expectError();

    $this->expectErrorMessage( 'Undefined index: href' );

    echo new Link( 'owl:sameAs', null );
  }

  public function testMissingRel() {
    $this->expectError();

    $this->expectErrorMessage( 'Undefined index: rel' );

    new Link( null, 'bar.json' );
  }
}
