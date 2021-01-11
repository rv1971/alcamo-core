<?php

namespace alcamo\exception;

/// File-related exception
class FileException extends \RuntimeException {
  public $filename;

  function __construct(
    $filename, $message = '', $code = 0, \Exception $previous = null
  ) {
    $this->filename = $filename;
    parent::__construct( $message, $code, $previous );
  }
}
