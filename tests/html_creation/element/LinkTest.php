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

    $this->assertSame( $attrs['rel'] ?? $rel, $link['rel'] );

    $this->assertSame( $attrs['href'] ?? $href, $link['href'] );

    $this->assertNull( $link->getContent() );

    $this->assertEquals( $expectedString, (string)$link );
  }

  public function allProvider() {
    return [
      'typical-use' => [
        'stylesheet',
        'foo.css',
        [ 'type' => 'text/css' ],
        '<link type="text/css" rel="stylesheet" href="foo.css"/>'
      ],

      'empty-args' => [
        null,
        null,
        [ 'href' => 'bar.html', 'rel' => 'dc:isVersionOf' ],
        '<link href="bar.html" rel="dc:isVersionOf"/>'
      ],

      'override-args' => [
        'dc:isVersionOf',
        'baz.php',
        [ 'href' => 'qux.php', 'rel' => 'dc:source' ],
        '<link href="qux.php" rel="dc:source"/>'
      ]
    ];
  }
}
