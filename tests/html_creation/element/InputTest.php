<?php

namespace alcamo\html_creation\element;

use PHPUnit\Framework\TestCase;

use alcamo\exception\InvalidEnumerator;
use alcamo\xml_creation\TokenList;

class InputTest extends TestCase {
  /**
   * @dataProvider allProvider
   */
  public function testAll(
    $type, $attrs, $expectedString
  ) {
    $input = new Input( $type, $attrs );

    $this->assertSame( 'input', $input->getTagName() );

    $this->assertInstanceOf( TokenList::class, $input['class'] );

    $this->assertSame( $type ?? $attrs['type'], $input['type'] );

    $this->assertNull( $input->getContent() );

    $this->assertEquals( $expectedString, (string)$input );
  }

  public function allProvider() {
    return [
      'typical-use' => [
        'text',
        [ 'name' => 'foo' ],
        '<input type="text" name="foo"/>'
      ],

      'empty-arg' => [
        null,
        [ 'type' => 'date', 'id' => 'startDate' ],
        '<input type="date" id="startDate"/>'
      ],

      'override-attrs' => [
        'date',
        [ 'type' => 'datetime-local', 'maxlength' => '30' ],
        '<input type="date" maxlength="30"/>'
      ]
    ];
  }

  public function testInvalidType() {
    $this->expectException( InvalidEnumerator::class );
    $this->expectExceptionMessage(
      'Invalid value "foo", expected one of: "'
      . implode( '", "', Input::TYPES )
      .'"; not a valid <input> type' );

    new Input( 'foo', [ 'name' => 'bar' ] );
  }

  public function testMissingType() {
    $this->expectError();

    $this->expectErrorMessage( 'Undefined index: type' );

    new Input( null, [ 'name' => 'bar' ] );
  }
}
