<?php

namespace alcamo\exception;

class SyntaxError extends \DomainException {
  public $text;
  public $offset;

  /** If $message starts with a ';', it is appended to the generated message,
   *  otherwise it replaces the generated one. */
  function __construct(
    string $text,
    ?int $offset = null,
    $message = null,
    $code = 0,
    \Exception $previous = null
  ) {
    $this->text = $text;
    $this->offset = $offset;

    if ( !$message || $message[0] == ';' ) {
      $message =
        "Syntax error in \"$text\""
        . (isset( $offset )
           ? (" at $offset: \"" . substr( $text, $offset, 10 ) . '..."')
           : '')
        . $message;
    }

    parent::__construct( $message, $code, $previous );
  }
}
