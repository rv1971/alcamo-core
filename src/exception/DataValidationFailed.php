<?php

namespace alcamo\exception;

/// Validation of data such as JSON or XML failed
class DataValidationFailed extends \RuntimeException
{
    public $data;
    public $uri;
    public $line;

    public function __construct(
        $data,
        $uri = null,
        $line = null,
        $message = '',
        $code = 0,
        \Exception $previous = null
    ) {
        $this->data = $data;
        $this->uri = $uri;
        $this->line = $line;

        if (!$message || $message[0] == ';') {
            $shortText =
                strlen($data) <= 40 ? $data : (substr($data, 0, 40) . '...');

            $automaticMessage = "Failed to validate \"$shortText\"";

            if (isset($uri)) {
                $automaticMessage .= " at $uri";
            }

            if (isset($line)) {
                $automaticMessage .= ", line $line";
            }

            $message = $automaticMessage . $message;
        }

        parent::__construct($message, $code, $previous);
    }
}
