<?php

namespace alcamo\exception;

/// Value not contained in enumeration
class ReadonlyViolation extends \LogicException {
  public $validValues;

  function __construct(
    ?object $object = null,
    ?string $method = null,
    $message = null,
    $code = 0,
    \Exception $previous = null
  ) {
    $this->object = $object ?? \debug_backtrace()[1]['object'];

    $this->method = $method ?? \debug_backtrace()[1]['function'];

    if ( !$message ) {
      $message = "Attempt to modify readonly " . get_class( $this->object )
        . " object through {$this->method}()";
    }

    parent::__construct( $message, $code, $previous );
  }
}
