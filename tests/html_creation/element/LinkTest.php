<?php

namespace alcamo\html_creation\element;

use PHPUnit\Framework\TestCase;

use alcamo\xml_creation\TokenList;

class LinkTest extends TestCase {
  /**
   * @dataProvider basicsProvider
   */
  public function testBasics(
    $rel, $href, $attrs, $expectedString
  ) {
    $link = new Link( $rel, $href, $attrs );

    $this->assertSame( 'link', $link->getTagName() );

    $this->assertInstanceOf( TokenList::class, $link['class'] );

    $this->assertSame( $rel, $link['rel'] );

    $this->assertSame( $href, $link['href'] );

    $this->assertNull( $link->getContent() );

    $this->assertEquals( $expectedString, (string)$link );
  }

  public function basicsProvider() {
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
