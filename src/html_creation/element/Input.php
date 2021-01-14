<?php

namespace alcamo\html_creation\element;

use alcamo\exception\InvalidEnumerator;
use alcamo\html_creation\AbstractSpecificElement;

class Input extends AbstractSpecificElement {
  const TYPES = [
    "button",
    "checkbox",
    "color",
    "date",
    "datetime-local",
    "email",
    "file",
    "hidden",
    "image",
    "month",
    "number",
    "password",
    "radio",
    "range",
    "reset",
    "search",
    "submit",
    "tel",
    "text",
    "time",
    "url",
    "week"
  ];

  public function __construct( $type, array $attrs ) {
    $attrs = (array)$attrs + compact( [ 'type' ] );

    if ( !in_array( $attrs['type'], static::TYPES ) ) {
      throw new InvalidEnumerator(
        $attrs['type'], static::TYPES, '; not a valid <input> type' );
    }

    parent::__construct( $attrs );
  }
}
