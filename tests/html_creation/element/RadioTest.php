<?php

namespace alcamo\html_creation\element;

use PHPUnit\Framework\TestCase;
use alcamo\xml_creation\TokenList;

class RadioTest extends TestCase
{
  /**
   * @dataProvider constructProvider
   */
    public function testConstruct(
        $name,
        $value,
        $compareTo,
        $attrs,
        $expectedString
    ) {
        $radio = new Radio($name, $value, $compareTo, $attrs);

        $this->assertSame('input', $radio->getTagName());

        $this->assertSame('radio', $radio['type']);

        $this->assertInstanceOf(TokenList::class, $radio['class']);

        if (isset($compareTo)) {
            $this->assertSame($value == $compareTo, $radio['checked']);
        }

        $this->assertEquals($expectedString, (string)$radio);
    }

    public function constructProvider()
    {
        return [
            'no-comparison' => [
                'foo',
                'bar',
                null,
                [ 'id' => 'BAR' ],
                '<input name="foo" value="bar" id="BAR" type="radio"/>'
            ],

            'unchecked' => [
                'foo',
                'bar',
                'barr',
                [ 'id' => 'BAR' ],
                '<input name="foo" value="bar" id="BAR" type="radio"/>'
            ],

            'checked' => [
                'baz',
                42,
                42,
                null,
                '<input name="baz" value="42" checked="checked" type="radio"/>'
            ]
        ];
    }
}
