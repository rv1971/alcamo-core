<?php

namespace alcamo\html_creation\element;

use PHPUnit\Framework\TestCase;
use alcamo\exception\InvalidEnumerator;
use alcamo\xml_creation\TokenList;

/* This also tests html_creation\Attribute */

class InputTest extends TestCase
{
  /**
   * @dataProvider basicsProvider
   */
    public function testBasics(
        $type,
        $attrs,
        $expectedString
    ) {
        $input = new Input($type, $attrs);

        $this->assertSame('input', $input->getTagName());

        $this->assertInstanceOf(TokenList::class, $input['class']);

        $this->assertSame($type, $input['type']);

        $this->assertNull($input->getContent());

        $this->assertEquals($expectedString, (string)$input);
    }

    public function basicsProvider()
    {
        return [
        'typical-use' => [
        'text',
        [ 'name' => 'foo', 'disabled' => false ],
        '<input type="text" name="foo"/>'
        ],

        'override-attrs' => [
        'date',
        [ 'type' => 'datetime-local', 'maxlength' => '30', 'disabled' => true ],
        '<input type="date" maxlength="30" disabled="disabled"/>'
        ]
        ];
    }

    public function testInvalidType()
    {
        $this->expectException(InvalidEnumerator::class);
        $this->expectExceptionMessage(
            'Invalid value "foo", expected one of: "'
            . implode('", "', Input::TYPES)
            . '"; not a valid <input> type'
        );

        new Input('foo', [ 'name' => 'bar' ]);
    }
}
