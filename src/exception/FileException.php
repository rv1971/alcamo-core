<?php

namespace alcamo\exception;

/// File-related exception
class FileException extends \RuntimeException
{
    public $filename;

    public function __construct(
        string $filename,
        string $message = '',
        int $code = 0,
        \Exception $previous = null
    ) {
        $this->filename = $filename;
        parent::__construct($message, $code, $previous);
    }
}
