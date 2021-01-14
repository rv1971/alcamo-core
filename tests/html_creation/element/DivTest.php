<?php

namespace alcamo\html_creation\element;

use PHPUnit\Framework\TestCase;

use alcamo\xml_creation\TokenList;

/* This also tests Element. */

class DivTest extends TestCase {
  /**
   * @dataProvider allProvider
   */
  public function testAll(
    $attrs, $content, $expectedClassCount, $expectedString
  ) {
    $div = new Div( $attrs, $content );

    $this->assertSame( 'div', $div->getTagName() );

    $this->assertInstanceOf( TokenList::class, $div['class'] );

    $this->assertEquals( $expectedClassCount, count( $div['class'] ) );

    $this->assertSame( $content, $div->getContent() );

    $this->assertEquals( $expectedString, (string)$div );
  }

  public function allProvider() {
    return [
      'empty-tag' => [ null, null, 0, '<div/>' ],

      'without-class' => [
        [ 'id' => 'foo' ],
        'Stet clita kasd gubergren',
        0,
        '<div id="foo">Stet clita kasd gubergren</div>'
      ],

      'with-class' => [
        [ 'class' => 'green bold collapsed' ],
        'At vero eos et accusam et justo duo dolores et ea rebum.',
        3,
        '<div class="green bold collapsed">At vero eos et accusam et justo duo dolores et ea rebum.</div>'
      ],

      'nested' => [
        [ 'class' => 'main' ],
        [
          new B( [ 'class' => 'red' ], 'Lorem' ),
          ' ipsum'
        ],
        1,
        '<div class="main"><b class="red">Lorem</b> ipsum</div>'
      ]
    ];
  }
}
