<?php

namespace alcamo\exception;

class MethodNotFound extends \BadMethodCallException
{
    public $objectOrLabel;
    public $method;

    /**
     * @param $objectOrLabel Either an object or a string describing a
     *  variable.
     *
     * If $message starts with a ';', it is appended to the generated message,
     * otherwise it replaces the generated one.
     */
    public function __construct(
        $objectOrLabel,
        string $method,
        string $message = '',
        int $code = 0,
        \Exception $previous = null
    ) {
        $this->objectOrLabel = $objectOrLabel;
        $this->method = $method;

        if (!$message || $message[0] == ';') {
            $message = "Method \"$method\" not found in "
                . (is_object($objectOrLabel)
                   ? get_class($objectOrLabel)
                   : $objectOrLabel)
                . $message;
        }

        parent::__construct($message, $code, $previous);
    }
}
