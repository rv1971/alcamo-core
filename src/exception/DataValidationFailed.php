<?php

namespace alcamo\exception;

/// Validation of data such as JSON or XML failed
class DataValidationFailed extends \RuntimeException
{
    public $data;
    public $uri;
    public $dataLine;

    public function __construct(
        $data,
        $uri = null,
        $dataLine = null,
        $message = '',
        $code = 0,
        \Exception $previous = null
    ) {
        $this->data = $data;
        $this->uri = $uri;
        $this->dataLine = $dataLine;

        if (!$message || $message[0] == ';') {
            $shortText =
                strlen($data) <= 40 ? $data : (substr($data, 0, 40) . '...');

            $automaticMessage = "Failed to validate \"$shortText\"";

            if (isset($uri)) {
                $automaticMessage .= " at $uri";
            }

            if (isset($dataLine)) {
                $automaticMessage .= ", line $dataLine";
            }

            $message = $automaticMessage . $message;
        }

        parent::__construct($message, $code, $previous);
    }
}
