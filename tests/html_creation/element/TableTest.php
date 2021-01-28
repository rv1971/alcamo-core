<?php

namespace alcamo\html_creation\element;

use Ds\Map;
use PHPUnit\Framework\TestCase;
use alcamo\xml_creation\TokenList;

/* This also tests Element. */

class TableTest extends TestCase
{
  /**
   * @dataProvider newFromCellsIterableProvider
   */
    public function testNewFromCellsIterable(
        $content,
        $attrs,
        $expectedString
    ) {
        $table = Table::newFromCellsIterable($content, $attrs);

        $this->assertSame('table', $table->getTagName());

        $this->assertInstanceOf(TokenList::class, $table['class']);

        $this->assertSame(
            count($content),
            count($table->getContent()->getContent())
        );

        $this->assertEquals($expectedString, (string)$table);
    }

    public function newFromCellsIterableProvider()
    {
        return [
        'empty' => [ [], null, '<table><tr></tr></table>' ],

        'array' => [
        [
          'FOO',
          new Td(42),
          new B('BAR'),
          new Th(new I('QUX'))
        ],
        [ 'id' => 'quux'],
        '<table id="quux"><tr><td>FOO</td><td>42</td><td><b>BAR</b></td><th><i>QUX</i></th></tr></table>',
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
        $table = Table::newFromRowsIterable($content, $attrs);

        $this->assertSame('table', $table->getTagName());

        $this->assertInstanceOf(TokenList::class, $table['class']);

        $this->assertSame(count($content), count($table->getContent()));

        if ($table->getContent()) {
            $this->assertInstanceOf(Tr::class, $table->getContent()[0]);
        }

        $this->assertEquals($expectedString, (string)$table);
    }

    public function newFromRowsIterableProvider()
    {
        return [
        'empty' => [ [], null, '<table></table>' ],

        'array' => [
        [
          [ 'FOO', new Td(42) ],
          [ new B('BAR'), new Th(new I('QUX')) ]
        ],
        [ 'id' => 'quux'],
        '<table id="quux"><tr><td>FOO</td><td>42</td></tr><tr><td><b>BAR</b></td><th><i>QUX</i></th></tr></table>',
        ]
        ];
    }

  /**
   * @dataProvider newFromRowgroupsProvider
   */
    public function testNewFromRowgroups(
        $thead,
        $body,
        $tfoot,
        $attrs,
        $expectedString
    ) {
        $table = Table::newFromRowgroups($thead, $body, $tfoot, $attrs);

        $this->assertSame('table', $table->getTagName());

        $this->assertInstanceOf(TokenList::class, $table['class']);

        $i = 0;

        if (isset($thead)) {
            $this->assertInstanceOf(Thead::class, $table->getContent()[$i++]);
        }

        if (isset($body)) {
            $this->assertInstanceOf(Tbody::class, $table->getContent()[$i++]);
        }

        if (isset($foot)) {
            $this->assertInstanceOf(Tfoot::class, $table->getContent()[$i++]);
        }

        $this->assertEquals($expectedString, (string)$table);
    }

    public function newFromRowgroupsProvider()
    {
        return [
        'typical-use' => [
        [ 'foo', 'bar' ],
        [
          [ new Th('f1'), 'b1' ],
          [ 'f2', 'b2' ]
        ],
        null,
        [ 'id' => 'overview' ],
        '<table id="overview">'
        . '<thead><tr><th>foo</th><th>bar</th></tr></thead>'
        . '<tbody><tr><th>f1</th><td>b1</td></tr><tr><td>f2</td><td>b2</td></tr></tbody>'
        . '</table>'
        ],

        'with-tr' => [
        new Tr([ 'foo', 'bar' ], null, Th::class),
        new Tr([ new Th('f1'), 'b1' ]),
        new Tr([ 'FOO', 'BAR' ]),
        null,
        '<table>'
        . '<thead><tr><th>foo</th><th>bar</th></tr></thead>'
        . '<tbody><tr><th>f1</th><td>b1</td></tr></tbody>'
        . '<tfoot><tr><td>FOO</td><td>BAR</td></tr></tfoot>'
        . '</table>'
        ],


        'with-objects' => [
        Thead::newFromCellsIterable([ 'foo', 'bar' ]),
        Tbody::newFromRowsIterable(
            [
            [ new Th('f1'), 'b1' ],
            [ 'f2', 'b2' ]
            ]
        ),
        Tfoot::newFromCellsIterable([ 'FOO', 'BAR' ]),
        new Map([ 'class' => 'green' ]),
        '<table class="green">'
        . '<thead><tr><th>foo</th><th>bar</th></tr></thead>'
        . '<tbody><tr><th>f1</th><td>b1</td></tr><tr><td>f2</td><td>b2</td></tr></tbody>'
        . '<tfoot><tr><td>FOO</td><td>BAR</td></tr></tfoot>'
        . '</table>'
        ]
        ];
    }
}
