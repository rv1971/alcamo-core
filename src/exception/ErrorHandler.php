<?php

namespace alcamo\exception;

/**
 * @brief Error handler that throws an error exception upon error
 *
 * Implements the RAAI pattern: An error handler is set by creating an
 * instance of this class, and reset when the destructor is executed.
 *
 * @date Last reviewed 2021-06-07
 */
class ErrorHandler
{
    /**
     * @brief The actual handler function
     *
     * Must be static, otherwise the destructor would never be called. The
     * parameters are explained in
     * [set_error_handler()](https://www.php.net/manual/en/function.set-error-handler).
     */
    public static function handler(
        int $errno,
        string $errstr,
        string $errfile = null,
        int $errline = null
    ): void {
        throw new \ErrorException($errstr, 0, $errno, $errfile, $errline);
    }

    /**
     * @param $errorTypes Error types that should be handled. Identical to the
     * second parameter of [set_error_handler()](https://www.php.net/manual/en/function.set-error-handler).
     */
    public function __construct(
        int $errorTypes = E_RECOVERABLE_ERROR | E_WARNING | E_NOTICE
    ) {
        set_error_handler(__CLASS__ . '::handler', $errorTypes);
    }

    public function __destruct()
    {
        restore_error_handler();
    }
}
