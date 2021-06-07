<?php

namespace alcamo\exception;

/**
 * @brief Exception related the state of an object
 *
 * @attention Any derived classes must define a public constant
 * MESSAGE_INCIPIT. It will be used to generate the message.
 *
 * @date Last reviewed 2021-06-07
 */
abstract class AbstractObjectStateException extends ProgramFlowException
{
    /// Incipit of the automatically generated message
    public const MESSAGE_INCIPIT = '';

    public $objectOrLabel; ///< Object, or string that indicates something

    /**
     * @param $objectOrLabel @copybrief $objectOrLabel
     *
     * @param $message If $message starts with a ';', it is appended to the
     * automatically generated message, otherwise it replaces the generated
     * one.
     */
    public function __construct(
        $objectOrLabel,
        string $message = '',
        int $code = 0,
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
