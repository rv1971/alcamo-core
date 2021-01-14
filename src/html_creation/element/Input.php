<?php

namespace alcamo\html_creation\element;

use alcamo\exception\InvalidEnumerator;
use alcamo\html_creation\AbstractSpecificElement;

class Input extends AbstractSpecificElement {
  const TAG_NAME = "input";

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
    if ( !isset( $type ) ) {
      $type = $attrs['type'];
    }

    if ( !in_array( $type, static::TYPES ) ) {
      throw new InvalidEnumerator(
        $type, static::TYPES, '; not a valid <input> type' );
    }

    parent::__construct( null, [ 'type' => $type ] + $attrs );
  }
}
