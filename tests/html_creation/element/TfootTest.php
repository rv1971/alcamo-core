<?php

namespace alcamo\html_creation\element;

use PHPUnit\Framework\TestCase;
use alcamo\xml_creation\TokenList;

/* This also tests Element. */

class TfootTest extends TestCase
{
  /**
   * @dataProvider newFromCellsIterableProvider
   */
    public function testNewFromCellsIterable(
        $content,
        $attrs,
        $expectedString
    ) {
        $tfoot = Tfoot::newFromCellsIterable($content, $attrs);

        $this->assertSame('tfoot', $tfoot->getTagName());

        $this->assertInstanceOf(TokenList::class, $tfoot['class']);

        $this->assertSame(
            count($content),
            count($tfoot->getContent()->getContent())
        );

        $this->assertEquals($expectedString, (string)$tfoot);
    }

    public function newFromCellsIterableProvider()
    {
        return [
        'empty' => [ [], null, '<tfoot><tr></tr></tfoot>' ],

        'array' => [
        [
          'FOO',
          new Td(42),
          new B('BAR'),
          new Th(new I('QUX'))
        ],
        [ 'id' => 'quux'],
        '<tfoot id="quux"><tr><td>FOO</td><td>42</td><td><b>BAR</b></td><th><i>QUX</i></th></tr></tfoot>',
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
        $tfoot = Tfoot::newFromRowsIterable($content, $attrs);

        $this->assertSame('tfoot', $tfoot->getTagName());

        $this->assertInstanceOf(TokenList::class, $tfoot['class']);

        $this->assertSame(count($content), count($tfoot->getContent()));

        if ($tfoot->getContent()) {
            $this->assertInstanceOf(Tr::class, $tfoot->getContent()[0]);
        }

        $this->assertEquals($expectedString, (string)$tfoot);
    }

    public function newFromRowsIterableProvider()
    {
        return [
        'empty' => [ [], null, '<tfoot></tfoot>' ],

        'array' => [
        [
          [ 'FOO', new Td(42) ],
          [ new B('BAR'), new Th(new I('QUX')) ]
        ],
        [ 'id' => 'quux'],
        '<tfoot id="quux"><tr><td>FOO</td><td>42</td></tr><tr><td><b>BAR</b></td><th><i>QUX</i></th></tr></tfoot>',
        ]
        ];
    }
}
