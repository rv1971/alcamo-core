<?php

namespace alcamo\html_creation\element;

use Ds\Map;
use PHPUnit\Framework\TestCase;
use alcamo\xml_creation\TokenList;

/* This also tests Element. */

class DivTest extends TestCase
{
  /**
   * @dataProvider basicsProvider
   */
    public function testBasics(
        $content,
        $attrs,
        $expectedClassCount,
        $expectedString
    ) {
        $div = new Div($content, $attrs);

        $this->assertSame('div', $div->getTagName());

        $this->assertInstanceOf(TokenList::class, $div['class']);

        $this->assertEquals($expectedClassCount, count($div['class']));

        $this->assertSame($content, $div->getContent());

        $this->assertEquals($expectedString, (string)$div);
    }

    public function basicsProvider()
    {
        return [
        'empty-tag' => [ null, null, 0, '<div/>' ],

        'without-class' => [
        'Stet clita kasd gubergren',
        new Map([ 'id' => 'foo' ]),
        0,
        '<div id="foo">Stet clita kasd gubergren</div>'
        ],

        'with-class' => [
        'At vero eos et accusam et justo duo dolores et ea rebum.',
        [ 'class' => 'green bold collapsed' ],
        3,
        '<div class="green bold collapsed">At vero eos et accusam et justo duo dolores et ea rebum.</div>'
        ],

        'nested' => [
        [
          new B('Lorem', [ 'class' => 'red' ]),
          ' ipsum'
        ],
        [ 'class' => 'main' ],
        1,
        '<div class="main"><b class="red">Lorem</b> ipsum</div>'
        ]
        ];
    }
}
