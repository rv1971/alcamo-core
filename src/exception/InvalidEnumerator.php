<?php

namespace alcamo\exception;

/// Value not contained in enumeration
class InvalidEnumerator extends ValueException {
  public $validValues;

  function __construct(
    $value,
    array $validValues,
    $message = null,
    $code = 0,
    \Exception $previous = null
  ) {
    $this->validValues = $validValues;

    if ( !$message ) {
      $message = "Invalid value '$value', expected one of: '"
        . implode( "', '", $validValues ) . "'";
    }

    parent::__construct( $value, $message, $code, $previous );
  }
}
