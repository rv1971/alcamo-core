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

    $this->assertSame( $attrs['type'] ?? $type, $input['type'] );

    $this->assertNull( $input->getContent() );

    $this->assertEquals( $expectedString, (string)$input );
  }

  public function allProvider() {
    return [
      'typical-use' => [
        'text',
        [ 'name' => 'foo' ],
        '<input name="foo" type="text"/>'
      ],

      'empty-arg' => [
        null,
        [ 'type' => 'date', 'id' => 'startDate' ],
        '<input type="date" id="startDate"/>'
      ],

      'override-arg' => [
        'date',
        [ 'type' => 'datetime-local', 'maxlength' => '30' ],
        '<input type="datetime-local" maxlength="30"/>'
      ]
    ];
  }

  public function testException() {
    $this->expectException( InvalidEnumerator::class );
    $this->expectExceptionMessage(
      'Invalid value "foo", expected one of: "'
      . implode( '", "', Input::TYPES )
      .'"; not a valid <input> type' );

    new Input( 'foo', [ 'name' => 'bar' ] );
  }
}
