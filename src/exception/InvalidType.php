<?php

namespace alcamo\exception;

/**
 * @brief Exception thrown when a value was not of a valid type
 *
 * @date Last reviewed 2021-06-07
 */
class InvalidType extends ValueException
{
    public $validTypes; ///< Names of valid types

    /**
     * @param $value @copybrief ValueException::$value
     *
     * @param $validTypes @copybrief $validtypes
     *
     * @param $message If $message starts with a ';', it is appended to the
     *  generated message, otherwise it replaces the generated one.
     */
    public function __construct(
        $value,
        array $validTypes,
        string $message = '',
        int $code = 0,
        \Exception $previous = null
    ) {
        $this->validTypes = $validTypes;

        if (!$message || $message[0] == ';') {
            $type = is_object($value) ? get_class($value) : gettype($value);

            $message = "Invalid type \"$type\", expected one of: \""
                . implode('", "', $validTypes) . '"'
                . $message;
        }

        parent::__construct($value, $message, $code, $previous);
    }
}
