<?php

namespace alcamo\html_creation\element;

use PHPUnit\Framework\TestCase;

use alcamo\xml_creation\TokenList;

class ScriptTest extends TestCase {
  /**
   * @dataProvider allProvider
   */
  public function testAll(
    $attrs, $content, $expectedString
  ) {
    $script = new Script( $attrs, $content );

    $this->assertSame( 'script', $script->getTagName() );

    $this->assertInstanceOf( TokenList::class, $script['class'] );

    $this->assertSame( $content ?? '', $script->getContent() );

    $this->assertEquals( $expectedString, (string)$script );
  }

  public function allProvider() {
    return [
      'typcial-use' => [
        [ 'href' => 'foo.js' ],
        null,
        '<script href="foo.js"></script>'
      ],

      'with-content' => [
        null,
        'alert( "Hello World!" );',
        '<script>alert( "Hello World!" );</script>'
      ],

      'with-json-content' => [
        [ 'type' => 'application/json' ],
        '{ "foo": "bar" }',
        '<script type="application/json">{ "foo": "bar" }</script>'
      ]
    ];
  }
}
