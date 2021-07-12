<?php

namespace alcamo\xml\exception;

use alcamo\exception\ValueException;

/**
 * @namespace alcamo::xml::exception
 *
 * @brief XML-specific exception classes
 */

/**
 * @brief Exception thrown when encountering an unknown namespace prefix
 *
 * @date Last reviewed 2021-06-15
 */
class UnknownNamespacePrefix extends \UnexpectedValueException
{
    public $prefix;        ///< Prefix that triggered the exception
    public $validPrefixes; ///< Valid prefixes

    /**
     * @param $prefix @copybrief $prefix
     *
     * @param $validPrefixes @copybrief $validPrefixes
     *
     * @param $message If $message starts with a ';', it is appended to the
     *  generated message, otherwise it replaces the generated one.
     */
    public function __construct(
        $prefix,
        ?array $validPrefixes = null,
        $message = null,
        $code = 0,
        \Exception $previous = null
    ) {
        $this->prefix = $prefix;
        $this->validPrefixes = $validPrefixes;

        if (!$message || $message[0] == ';') {
            $message = "Unknown namespace prefix \"$prefix\""
                . (isset($validPrefixes)
                   ? (", expected one of: \""
                      . implode("\", \"", $validPrefixes) . '"')
                   : '')
                . $message;
        }

        parent::__construct($message, $code, $previous);
    }
}
