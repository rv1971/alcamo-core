<?php

namespace alcamo\html_creation\element;

use Ds\{Map, Set};
use PHPUnit\Framework\TestCase;
use alcamo\xml_creation\TokenList;

/* This also tests Element. */

class SelectTest extends TestCase
{
  /**
   * @dataProvider newFromValueSequenceProvider
   */
    public function testNewFromValueSequence(
        $name,
        $values,
        $compareTo,
        $attrs,
        $expectedString
    ) {
        $select =
        Select::newFromValueSequence($name, $values, $compareTo, $attrs);

        $this->assertSame('select', $select->getTagName());

        $this->assertInstanceOf(TokenList::class, $select['class']);

        $this->assertSame(count($values), count($select->getContent()));

        $this->assertEquals($expectedString, (string)$select);
    }

    public function newFromValueSequenceProvider()
    {
        return [
        'empty' => [
        null,
        [],
        null,
        null,
        '<select></select>'
        ],

        'array' => [
        'foo[]',
        [ 'a', 'b', 'c', 'd' ],
        new Set([ 'a', 'd' ]),
        [ 'id' => 'foo' ],
        '<select name="foo[]" id="foo" multiple="multiple">'
        . '<option selected="selected">a</option>'
        . '<option>b</option>'
        . '<option>c</option>'
        . '<option selected="selected">d</option>'
        . '</select>'
        ],

        'set' => [
        'bar',
        new Set([ 1, 3, 5, 7, 11, 13, 17 ]),
        [ 3, 7, 17 ],
        null,
        '<select name="bar">'
        . '<option>1</option>'
        . '<option selected="selected">3</option>'
        . '<option>5</option>'
        . '<option selected="selected">7</option>'
        . '<option>11</option>'
        . '<option>13</option>'
        . '<option selected="selected">17</option>'
        . '</select>'
        ]
        ];
    }

  /**
   * @dataProvider newFromMapProvider
   */
    public function testNewFromMap(
        $name,
        $values,
        $compareTo,
        $attrs,
        $expectedString
    ) {
        $select = Select::newFromMap($name, $values, $compareTo, $attrs);

        $this->assertSame('select', $select->getTagName());

        $this->assertInstanceOf(TokenList::class, $select['class']);

        $this->assertSame(count($values), count($select->getContent()));

        $this->assertEquals($expectedString, (string)$select);
    }

    public function newFromMapProvider()
    {
        return [
        'empty' => [
        'nothing',
        [],
        null,
        null,
        '<select name="nothing"></select>'
        ],

        'array' => [
        'foo',
        [
          'a' => 'Ancona',
          'b' => 'Bologna',
          'c' => 'Cagliari',
          'd' => 'Domodossola'
        ],
        new Set([ 'a', 'd' ]),
        [ 'id' => 'foo' ],
        '<select name="foo" id="foo">'
        . '<option value="a" selected="selected">Ancona</option>'
        . '<option value="b">Bologna</option>'
        . '<option value="c">Cagliari</option>'
        . '<option value="d" selected="selected">Domodossola</option>'
        . '</select>'
        ],

        'set' => [
        'bar[]',
        new Map([
          1 => 'one',
          3 => 'three',
          5 => 'five',
          7 => 'seven',
          11 => 'eleven',
          13 => 'thirteen',
          17 => 'seventeen'
        ]),
        [ 3, 7, 17 ],
        null,
        '<select name="bar[]" multiple="multiple">'
        . '<option value="1">one</option>'
        . '<option value="3" selected="selected">three</option>'
        . '<option value="5">five</option>'
        . '<option value="7" selected="selected">seven</option>'
        . '<option value="11">eleven</option>'
        . '<option value="13">thirteen</option>'
        . '<option value="17" selected="selected">seventeen</option>'
        . '</select>'
        ]
        ];
    }
}
