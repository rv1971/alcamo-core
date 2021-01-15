<?php

namespace alcamo\html_creation\element;

class Checkbox extends Input {
  function __construct(
    string $name, $value, $compareTo = null, ?array $attrs = null
  ) {
    $attrs = compact( [ 'name', 'value' ] ) + (array)$attrs;

    if ( isset( $compareTo ) ) {
      switch ( true ) {
        case is_callable( [ $compareTo, 'contains' ] ):
          $attrs['checked'] = $compareTo->contains( $value );
          break;

        case is_array( $compareTo ):
          $attrs['checked'] = in_array( $value, $compareTo, true );
          break;

        default:
          $attrs['checked'] = $value == $compareTo;
      }
    }

    parent::__construct( 'checkbox', $attrs );
  }
}
