<?php

namespace alcamo\exception;

/**
 * @brief Exception thrown when attempting to call a non-existent method.
 *
 * Typically used in the magic method __call().
 *
 * @date Last reviewed 2021-06-07
 */
class MethodNotFound extends \BadMethodCallException
{
    public $objectOrLabel; ///< Object, or string that indicates something
    public $method;        ///< Method name

    /**
     * @param $objectOrLabel @copybrief $objectOrLabel
     *
     * @param $method @copybrief $method
     *
     * @param $message If $message starts with a ';', it is appended to the
     * automatically generated message, otherwise it replaces the generated
     * one.
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
