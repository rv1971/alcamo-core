<?php

namespace alcamo\exception;

/**
 * @brief Exception thrown when attempting to use an unsupported feature
 *
 * @date Last reviewed 2021-06-07
 */
class Unsupported extends \LogicException
{
    public $label; ///< Label of the feature that triggered the exception

    /**
     * @param $label @copybrief $label
     *
     * @param $message If $message starts with a ';', it is appended to the
     *  generated message, otherwise it replaces the generated one.
     */
    public function __construct(
        $label,
        string $message = '',
        int $code = 0,
        \Exception $previous = null
    ) {
        $this->label = $label;

        if (!$message || $message[0] == ';') {
            $message = "$label not supported$message";
        }

        parent::__construct($message, $code, $previous);
    }
}
