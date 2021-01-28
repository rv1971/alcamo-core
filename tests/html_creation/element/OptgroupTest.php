<?php

namespace alcamo\html_creation\element;

use Ds\{Map, Set};
use PHPUnit\Framework\TestCase;
use alcamo\xml_creation\TokenList;

/* This also tests Element. */

class OptgroupTest extends TestCase
{
  /**
   * @dataProvider newFromValueSequenceProvider
   */
    public function testNewFromValueSequence(
        $label,
        $values,
        $compareTo,
        $attrs,
        $expectedString
    ) {
        $optgroup =
        Optgroup::newFromValueSequence($label, $values, $compareTo, $attrs);

        $this->assertSame('optgroup', $optgroup->getTagName());

        $this->assertInstanceOf(TokenList::class, $optgroup['class']);

        $this->assertSame(count($values), count($optgroup->getContent()));

        $this->assertEquals($expectedString, (string)$optgroup);
    }

    public function newFromValueSequenceProvider()
    {
        return [
        'empty' => [
        'None',
        [],
        null,
        null,
        '<optgroup label="None"></optgroup>'
        ],

        'array' => [
        'Foo',
        [ 'a', 'b', 'c', 'd' ],
        new Set([ 'a', 'd' ]),
        [ 'id' => 'foo' ],
        '<optgroup label="Foo" id="foo">'
        . '<option selected="selected">a</option>'
        . '<option>b</option>'
        . '<option>c</option>'
        . '<option selected="selected">d</option>'
        . '</optgroup>'
        ],

        'set' => [
        'Bar',
        new Set([ 1, 3, 5, 7, 11, 13, 17 ]),
        [ 3, 7, 17 ],
        null,
        '<optgroup label="Bar">'
        . '<option>1</option>'
        . '<option selected="selected">3</option>'
        . '<option>5</option>'
        . '<option selected="selected">7</option>'
        . '<option>11</option>'
        . '<option>13</option>'
        . '<option selected="selected">17</option>'
        . '</optgroup>'
        ]
        ];
    }

  /**
   * @dataProvider newFromMapProvider
   */
    public function testNewFromMap(
        $label,
        $values,
        $compareTo,
        $attrs,
        $expectedString
    ) {
        $optgroup = Optgroup::newFromMap($label, $values, $compareTo, $attrs);

        $this->assertSame('optgroup', $optgroup->getTagName());

        $this->assertInstanceOf(TokenList::class, $optgroup['class']);

        $this->assertSame(count($values), count($optgroup->getContent()));

        $this->assertEquals($expectedString, (string)$optgroup);
    }

    public function newFromMapProvider()
    {
        return [
        'empty' => [
        'None',
        [],
        null,
        null,
        '<optgroup label="None"></optgroup>'
        ],

        'array' => [
        'Foo',
        [
          'a' => 'Ancona',
          'b' => 'Bologna',
          'c' => 'Cagliari',
          'd' => 'Domodossola'
        ],
        new Set([ 'a', 'd' ]),
        [ 'id' => 'foo' ],
        '<optgroup label="Foo" id="foo">'
        . '<option value="a" selected="selected">Ancona</option>'
        . '<option value="b">Bologna</option>'
        . '<option value="c">Cagliari</option>'
        . '<option value="d" selected="selected">Domodossola</option>'
        . '</optgroup>'
        ],

        'set' => [
        'Bar',
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
        '<optgroup label="Bar">'
        . '<option value="1">one</option>'
        . '<option value="3" selected="selected">three</option>'
        . '<option value="5">five</option>'
        . '<option value="7" selected="selected">seven</option>'
        . '<option value="11">eleven</option>'
        . '<option value="13">thirteen</option>'
        . '<option value="17" selected="selected">seventeen</option>'
        . '</optgroup>'
        ]
        ];
    }
}
