<?php

namespace alcamo\html_creation\element;

use Ds\Set;
use PHPUnit\Framework\TestCase;
use alcamo\collection\Collection;
use alcamo\xml_creation\TokenList;

class OptionTest extends TestCase
{
  /**
   * @dataProvider constructProvider
   */
    public function testConstruct(
        $value,
        $content,
        $compareTo,
        $attrs,
        $expectedString
    ) {
        $option = new Option($value, $content, $compareTo, $attrs);

        $this->assertSame('option', $option->getTagName());

        if (isset($content)) {
            $this->assertSame($value, $option['value']);
        }

        $this->assertInstanceOf(TokenList::class, $option['class']);

        $this->assertSame($content ?? $value, $option->getContent());

        $this->assertEquals($expectedString, (string)$option);
    }

    public function constructProvider()
    {
        return [
        'no-comparison' => [
        'bar',
        null,
        null,
        [ 'id' => 'BAR' ],
        '<option id="BAR">bar</option>'
        ],

        'no-value' => [
        null,
        'Bar',
        null,
        [ 'id' => 'BAR' ],
        '<option id="BAR">Bar</option>'
        ],

        'unselected-string' => [
        'bar',
        'foo',
        'barr',
        [ 'id' => 'BAR' ],
        '<option value="bar" id="BAR">foo</option>'
        ],

        'selected-string' => [
        42,
        'baz',
        42,
        null,
        '<option value="42" selected="selected">baz</option>'
        ],

        'unselected-array' => [
        'bar',
        'foo',
        [ 'barr', 'quuux' ],
        [ 'id' => 'BAR' ],
        '<option value="bar" id="BAR">foo</option>'
        ],

        'selected-array' => [
        42,
        'baz',
        [ 41, 42, 43 ],
        null,
        '<option value="42" selected="selected">baz</option>'
        ],

        'unselected-set' => [
        'bar',
        'foo',
        new Set([ 'barr', 'quuux' ]),
        [ 'id' => 'BAR' ],
        '<option value="bar" id="BAR">foo</option>'
        ],

        'selected-set' => [
        42,
        'baz',
        new Set([ 41, 42, 43 ]),
        null,
        '<option value="42" selected="selected">baz</option>'
        ],

        'unselected-collection' => [
        'bar',
        'foo',
        new Collection([ 'barr', 'quuux' ]),
        [ 'id' => 'BAR' ],
        '<option value="bar" id="BAR">foo</option>'
        ],

        'selected-collection' => [
        42,
        'baz',
        new Collection([ 41, 42, 43 ]),
        null,
        '<option value="42" selected="selected">baz</option>'
        ]
        ];
    }
}
