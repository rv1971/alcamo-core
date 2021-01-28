<?php

namespace alcamo\exception;

/// Value-related exception
class ValueException extends \DomainException
{
    public $value;

    public function __construct(
        $value,
        $message = '',
        $code = 0,
        \Exception $previous = null
    ) {
        $this->value = $value;
        parent::__construct($message, $code, $previous);
    }
}
