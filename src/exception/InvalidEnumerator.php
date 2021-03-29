<?php

namespace alcamo\exception;

/// Value not contained in enumeration
class InvalidEnumerator extends ValueException
{
    public $validValues;

    /** If $message starts with a ';', it is appended to the generated message,
     *  otherwise it replaces the generated one. */
    public function __construct(
        $value,
        array $validValues,
        string $message = '',
        int $code = 0,
        \Exception $previous = null
    ) {
        $this->validValues = $validValues;

        if (!$message || $message[0] == ';') {
            $message = "Invalid value \"$value\", expected one of: \""
                . implode("\", \"", $validValues) . '"'
                . $message;
        }

        parent::__construct($value, $message, $code, $previous);
    }
}
