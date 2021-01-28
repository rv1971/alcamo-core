<?php

namespace alcamo\html_creation\element;

use PHPUnit\Framework\TestCase;
use alcamo\xml_creation\TokenList;

/* This also tests Element. */

class TheadTest extends TestCase
{
  /**
   * @dataProvider newFromCellsIterableProvider
   */
    public function testNewFromCellsIterable(
        $content,
        $attrs,
        $expectedString
    ) {
        $thead = Thead::newFromCellsIterable($content, $attrs);

        $this->assertSame('thead', $thead->getTagName());

        $this->assertInstanceOf(TokenList::class, $thead['class']);

        $this->assertSame(
            count($content),
            count($thead->getContent()->getContent())
        );

        $this->assertEquals($expectedString, (string)$thead);
    }

    public function newFromCellsIterableProvider()
    {
        return [
        'empty' => [ [], null, '<thead><tr></tr></thead>' ],

        'array' => [
        [
          'FOO',
          new Td(42),
          new B('BAR'),
          new Th(new I('QUX'))
        ],
        [ 'id' => 'quux'],
        '<thead id="quux"><tr><th>FOO</th><td>42</td><th><b>BAR</b></th><th><i>QUX</i></th></tr></thead>',
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
        $thead = Thead::newFromRowsIterable($content, $attrs);

        $this->assertSame('thead', $thead->getTagName());

        $this->assertInstanceOf(TokenList::class, $thead['class']);

        $this->assertSame(count($content), count($thead->getContent()));

        if ($thead->getContent()) {
            $this->assertInstanceOf(Tr::class, $thead->getContent()[0]);
        }

        $this->assertEquals($expectedString, (string)$thead);
    }

    public function newFromRowsIterableProvider()
    {
        return [
        'empty' => [ [], null, '<thead></thead>' ],

        'array' => [
        [
          [ 'FOO', new Td(42) ],
          [ new B('BAR'), new Th(new I('QUX')) ]
        ],
        [ 'id' => 'quux'],
        '<thead id="quux"><tr><th>FOO</th><td>42</td></tr><tr><th><b>BAR</b></th><th><i>QUX</i></th></tr></thead>',
        ]
        ];
    }
}
