<?php

namespace alcamo\html_creation\element;

use PHPUnit\Framework\TestCase;

use alcamo\xml_creation\TokenList;

class ScriptTest extends TestCase {
  /**
   * @dataProvider allProvider
   */
  public function testAll(
    $content, $attrs, $expectedString
  ) {
    $script = new Script( $content, $attrs );

    $this->assertSame( 'script', $script->getTagName() );

    $this->assertInstanceOf( TokenList::class, $script['class'] );

    $this->assertSame( $content ?? '', $script->getContent() );

    $this->assertEquals( $expectedString, (string)$script );
  }

  public function allProvider() {
    return [
      'typcial-use' => [
        null,
        [ 'href' => 'foo.js' ],
        '<script href="foo.js"></script>'
      ],

      'with-content' => [
        'alert( "Hello World!" );',
        null,
        '<script>alert( "Hello World!" );</script>'
      ],

      'with-json-content' => [
        '{ "foo": "bar" }',
        [ 'type' => 'application/json' ],
        '<script type="application/json">{ "foo": "bar" }</script>'
      ]
    ];
  }
}
