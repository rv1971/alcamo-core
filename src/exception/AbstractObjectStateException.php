<?php

namespace alcamo\exception;

abstract class AbstractObjectStateException extends ProgramFlowException
{
    public $objectOrLabel;

    /**
     * @param $objectOrLabel Either an object or a string describing a
     *  variable.
     *
     * If $message starts with a ';', it is appended to the generated message,
     * otherwise it replaces the generated one.
     */
    public function __construct(
        $objectOrLabel,
        $message = null,
        $code = 0,
        \Exception $previous = null
    ) {
        $this->objectOrLabel = $objectOrLabel;

        if (!$message || $message[0] == ';') {
            $message = static::MESSAGE_INCIPIT . ' '
                . (is_object($objectOrLabel)
                   ? get_class($objectOrLabel)
                   : $objectOrLabel)
                . $message;
        }

        parent::__construct($message, $code, $previous);
    }
}
