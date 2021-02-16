<?php

namespace alcamo\exception;

class Unsupported extends \LogicException
{
    public $label;

    /**
     * If $message starts with a ';', it is appended to the generated message,
     * otherwise it replaces the generated one.
     */
    public function __construct(
        $label,
        $message = null,
        $code = 0,
        \Exception $previous = null
    ) {
        $this->label = $label;

        if (!$message || $message[0] == ';') {
            $message = "$label not supported$message";
        }

        parent::__construct($message, $code, $previous);
    }
}
