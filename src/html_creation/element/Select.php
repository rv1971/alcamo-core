<?php

namespace alcamo\html_creation\element;

class Select extends AbstractOptionList {
  const TAG_NAME = "select";

  /// Create from sequence of values
  public static function newFromValueSequence(
    $name, iterable $values, $compareTo = null, ?array $attrs = null
  ) {
    return new self(
      $name,
      self::createOptionArrayFromSequence( $values, $compareTo ),
      $attrs
    );
  }

  /// Create from map of values to contents
  public static function newFromMap(
    $name, iterable $values, $compareTo = null, ?array $attrs = null
  ) {
    return new self(
      $name,
      self::createOptionArrayFromMap( $values, $compareTo ),
      $attrs
    );
  }

  public function __construct( $name, $content, ?array $attrs = null ) {
    $attrs = compact( 'name' ) + (array)$attrs;

    if ( isset( $name ) && substr( $name, -2 ) == '[]' ) {
      $attrs['multiple'] = true;
    }

    parent::__construct( $content, $attrs );
  }
}
