<?php

namespace alcamo\exception;

class ErrorHandler
{
    public function __construct(
        int $errorTypes = E_RECOVERABLE_ERROR | E_WARNING | E_NOTICE
    ) {
        set_error_handler(__CLASS__ . '::handler', $errorTypes);
    }

    /** Implement the RAAI pattern. */
    public function __destruct()
    {
        restore_error_handler();
    }

    // must be static, otherwise the destructor will never be called
    public static function handler(
        int $errno,
        string $errstr,
        string $errfile = null,
        int $errline = null
    ) {
        throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
}
