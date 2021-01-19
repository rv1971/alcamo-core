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

  /**
   * @dataProvider newFromRelAndLocalUrlProvider
   */
  public function testNewFromLocalUrl(
    $rel, $href, $attrs, $path, $expectedString
  ) {
    $link = Link::newFromRelAndLocalUrl( $rel, $href, $attrs, $path );

    $this->assertSame( 'link', $link->getTagName() );

    $this->assertInstanceOf( TokenList::class, $link['class'] );

    $this->assertSame( $rel, $link['rel'] );

    $this->assertNull( $link->getContent() );

    $this->assertEquals( $expectedString, (string)$link );
  }

  public function newFromRelAndLocalUrlProvider() {
    $baseDir = __DIR__ . DIRECTORY_SEPARATOR;

    $mCss = gmdate( 'YmdHis', filemtime( "${baseDir}alcamo.css" ) );

    $baseDir2 = dirname( dirname( __DIR__ ) ) . DIRECTORY_SEPARATOR
      . 'alcamo' . DIRECTORY_SEPARATOR;

    $mJson = gmdate( 'YmdHis', filemtime( "${baseDir2}foo.json" ) );

    return [
      'css' => [
        'stylesheet',
        "${baseDir}alcamo.css",
        [ 'disable' => true ],
        null,
        "<link rel=\"stylesheet\" href=\"${baseDir}alcamo.css?m=$mCss\" disable=\"disable\"/>"
      ],
      'json' => [
        'manifest',
        "/foo.json?baz=qux",
        [ 'id' => 'FOO' ],
        "${baseDir2}foo.json",
        "<link rel=\"manifest\" href=\"/foo.json?baz=qux&amp;m=$mJson\" type=\"application/json\" id=\"FOO\"/>"
      ],
      'explicit-type' => [
        'dc:isVersionOf',
        "/foo.json?baz=qux",
        [ 'type' => 'application/x-quux' ],
        "${baseDir2}foo.json",
        "<link rel=\"dc:isVersionOf\" href=\"/foo.json?baz=qux&amp;m=$mJson\" type=\"application/x-quux\"/>"
      ]
    ];
  }
}
