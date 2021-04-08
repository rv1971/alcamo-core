<?php

namespace alcamo\exception;

class InvalidType extends ValueException
{
    public $validTypes;

    /** If $message starts with a ';', it is appended to the generated message,
     *  otherwise it replaces the generated one. */
    public function __construct(
        $value,
        array $validTypes,
        string $message = '',
        int $code = 0,
        \Exception $previous = null
    ) {
        $this->validTypes = $validTypes;

        if (!$message || $message[0] == ';') {
            $type = is_object($value) ? get_class($value) : gettype($value);

            $message = "Invalid type \"$type\", expected one of: \""
                . implode('", "', $validTypes) . '"'
                . $message;
        }

        parent::__construct($value, $message, $code, $previous);
    }
}
