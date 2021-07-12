<?php

namespace alcamo\exception;

/**
 * @namespace alcamo::exception
 *
 * @brief General-purpsoe exception classes
 */

/**
 * @brief Exception related to a specific value
 *
 * @date Last reviewed 2021-06-07
 */
class ValueException extends \UnexpectedValueException
{
    public $value; ///< Value that triggered the exception

    /**
     * @param $value @copybrief $value
     */
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
