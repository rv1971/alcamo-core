<?php

namespace alcamo\html_creation\element;

class Option extends AbstractSpecificElement {
  const TAG_NAME = "option";

  function __construct(
    $value, $content = null, $compareTo = null, ?array $attrs = null
  ) {
    /* If $content is unset, $value will be used as content, and in this case
     * the `value` attribute is redundant. */
    $attrs = isset( $content )
      ? compact( 'value' ) + (array)$attrs
      : (array)$attrs;

    if ( isset( $compareTo ) ) {
      switch ( true ) {
        case is_callable( [ $compareTo, 'contains' ] ):
          $attrs['selected'] = $compareTo->contains( $value );
          break;

        case is_array( $compareTo ):
          $attrs['selected'] = in_array( $value, $compareTo, true );
          break;

        default:
          $attrs['selected'] = $value == $compareTo;
      }
    }

    parent::__construct( $content ?? $value, $attrs );
  }
}
