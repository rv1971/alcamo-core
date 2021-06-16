<?php

namespace alcamo\html_creation\element;

use PHPUnit\Framework\TestCase;
use alcamo\xml_creation\TokenList;

class HiddenTest extends TestCase
{
  /**
   * @dataProvider constructProvider
   */
    public function testConstruct(
        $name,
        $value,
        $attrs,
        $expectedString
    ) {
        $hidden = new Hidden($name, $value, $attrs);

        $this->assertSame('input', $hidden->getTagName());

        $this->assertSame('hidden', $hidden['type']);

        $this->assertInstanceOf(TokenList::class, $hidden['class']);

        $this->assertEquals($expectedString, (string)$hidden);
    }

    public function constructProvider()
    {
        return [
            'typical-use' => [
                'foo',
                'bar',
                null,
                '<input name="foo" value="bar" type="hidden"/>'
            ],

            'with-attrs' => [
                'foo',
                'bar',
                [ 'id' => 'QUX' ],
                '<input name="foo" value="bar" id="QUX" type="hidden"/>'
            ]
        ];
    }
}
