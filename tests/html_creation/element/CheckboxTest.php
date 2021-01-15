<?php

namespace alcamo\html_creation\element;

use Ds\Set;
use PHPUnit\Framework\TestCase;

use alcamo\collection\Collection;
use alcamo\xml_creation\TokenList;

class CheckboxTest extends TestCase {
  /**
   * @dataProvider constructProvider
   */
  public function testConstruct(
    $name, $value, $compareTo, $attrs, $expectedString
  ) {
    $checkbox = new Checkbox( $name, $value, $compareTo, $attrs );

    $this->assertSame( 'input', $checkbox->getTagName() );

    $this->assertSame( 'checkbox', $checkbox['type'] );

    $this->assertInstanceOf( TokenList::class, $checkbox['class'] );

    $this->assertEquals( $expectedString, (string)$checkbox );
  }

  public function constructProvider() {
    return [
      'no-comparison' => [
        'foo',
        'bar',
        null,
        [ 'id' => 'BAR' ],
        '<input type="checkbox" name="foo" value="bar" id="BAR"/>'
      ],

      'unchecked-string' => [
        'foo',
        'bar',
        'barr',
        [ 'id' => 'BAR' ],
        '<input type="checkbox" name="foo" value="bar" id="BAR"/>'
      ],

      'checked-string' => [
        'baz',
        42,
        42,
        null,
        '<input type="checkbox" name="baz" value="42" checked="checked"/>'
      ],

      'unchecked-array' => [
        'foo',
        'bar',
        [ 'barr', 'quuux' ],
        [ 'id' => 'BAR' ],
        '<input type="checkbox" name="foo" value="bar" id="BAR"/>'
      ],

      'checked-array' => [
        'baz',
        42,
        [ 41, 42, 43 ],
        null,
        '<input type="checkbox" name="baz" value="42" checked="checked"/>'
      ],

      'unchecked-set' => [
        'foo',
        'bar',
        new Set( [ 'barr', 'quuux' ] ),
        [ 'id' => 'BAR' ],
        '<input type="checkbox" name="foo" value="bar" id="BAR"/>'
      ],

      'checked-set' => [
        'baz',
        42,
        new Set( [ 41, 42, 43 ] ),
        null,
        '<input type="checkbox" name="baz" value="42" checked="checked"/>'
      ],

      'unchecked-collection' => [
        'foo',
        'bar',
        new Collection( [ 'barr', 'quuux' ] ),
        [ 'id' => 'BAR' ],
        '<input type="checkbox" name="foo" value="bar" id="BAR"/>'
      ],

      'checked-collection' => [
        'baz',
        42,
        new Collection( [ 41, 42, 43 ] ),
        null,
        '<input type="checkbox" name="baz" value="42" checked="checked"/>'
      ]
    ];
  }
}
