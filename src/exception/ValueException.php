<?php

namespace alcamo\exception;

/// Value-related exception
class ValueException extends \UnexpectedValueException
{
    public $value;

    public function __construct(
        $value,
        string $message = '',
        int $code = 0,
        \Exception $previous = null
    ) {
        $this->value = $value;
        parent::__construct($message, $code, $previous);
    }
}
