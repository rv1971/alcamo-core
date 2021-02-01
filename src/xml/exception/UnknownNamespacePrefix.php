<?php

namespace alcamo\xml\exception;

use alcamo\exception\ValueException;

/// Unknown namespace prefix
class UnknownNamespacePrefix extends \UnexpectedValueException
{
    public $prefix;
    public $validPrefixes;

    /** If $message starts with a ';', it is appended to the generated message,
     *  otherwise it replaces the generated one. */
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
