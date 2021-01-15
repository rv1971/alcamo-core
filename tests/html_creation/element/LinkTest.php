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

      'override-attrs' => [
        'dc:isVersionOf',
        'baz.php',
        [ 'href' => 'qux.php', 'rel' => 'dc:source' ],
        '<link rel="dc:isVersionOf" href="baz.php"/>'
      ]
    ];
  }
}
