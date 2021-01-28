<?php

namespace alcamo\html_creation\element;

use PHPUnit\Framework\TestCase;
use alcamo\xml_creation\TokenList;

/* This also tests Element. */

class TbodyTest extends TestCase
{
  /**
   * @dataProvider newFromCellsIterableProvider
   */
    public function testNewFromCellsIterable(
        $content,
        $attrs,
        $expectedString
    ) {
        $tbody = Tbody::newFromCellsIterable($content, $attrs);

        $this->assertSame('tbody', $tbody->getTagName());

        $this->assertInstanceOf(TokenList::class, $tbody['class']);

        $this->assertSame(
            count($content),
            count($tbody->getContent()->getContent())
        );

        $this->assertEquals($expectedString, (string)$tbody);
    }

    public function newFromCellsIterableProvider()
    {
        return [
        'empty' => [ [], null, '<tbody><tr></tr></tbody>' ],

        'array' => [
        [
          'FOO',
          new Td(42),
          new B('BAR'),
          new Th(new I('QUX'))
        ],
        [ 'id' => 'quux'],
        '<tbody id="quux"><tr><td>FOO</td><td>42</td><td><b>BAR</b></td><th><i>QUX</i></th></tr></tbody>',
        ]
        ];
    }

  /**
   * @dataProvider newFromRowsIterableProvider
   */
    public function testNewFromRowsIterable(
        $content,
        $attrs,
        $expectedString
    ) {
        $tbody = Tbody::newFromRowsIterable($content, $attrs);

        $this->assertSame('tbody', $tbody->getTagName());

        $this->assertInstanceOf(TokenList::class, $tbody['class']);

        $this->assertSame(count($content), count($tbody->getContent()));

        if ($tbody->getContent()) {
            $this->assertInstanceOf(Tr::class, $tbody->getContent()[0]);
        }

        $this->assertEquals($expectedString, (string)$tbody);
    }

    public function newFromRowsIterableProvider()
    {
        return [
        'empty' => [ [], null, '<tbody></tbody>' ],

        'array' => [
        [
          [ 'FOO', new Td(42) ],
          [ new B('BAR'), new Th(new I('QUX')) ]
        ],
        [ 'id' => 'quux'],
        '<tbody id="quux"><tr><td>FOO</td><td>42</td></tr><tr><td><b>BAR</b></td><th><i>QUX</i></th></tr></tbody>',
        ]
        ];
    }
}
