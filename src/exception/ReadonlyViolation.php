<?php

namespace alcamo\exception;

/// Value not contained in enumeration
class ReadonlyViolation extends \LogicException
{
    public $validValues;

    /** If $message starts with a ';', it is appended to the generated message,
     *  otherwise it replaces the generated one. */
    public function __construct(
        ?object $object = null,
        ?string $method = null,
        string $message = '',
        int $code = 0,
        \Exception $previous = null
    ) {
        $this->object = $object ?? \debug_backtrace()[1]['object'];

        $this->method = $method ?? \debug_backtrace()[1]['function'];

        if (!$message || $message[0] == ';') {
            $message = "Attempt to modify readonly " . get_class($this->object)
                . " object through {$this->method}()"
                . $message;
        }

        parent::__construct($message, $code, $previous);
    }
}
