<?php

namespace alcamo\exception;

/// File not found
class FileNotFound extends FileException {
  public $places;

  function __construct(
    $filename,
    ?string $places = null,
    $message = null,
    $code = 0,
    \Exception $previous = null
  ) {
    $this->places = $places;

    if ( !$message ) {
      $message = "File '$filename' not found";

      if ( isset( $places ) ) {
        $message .= " in '$places'";
      }
    }

    parent::__construct( $filename, $message, $code, $previous );
  }
}
