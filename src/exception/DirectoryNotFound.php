<?php

namespace alcamo\exception;

/**
 * @brief Exception thrown when a directory was not found
 *
 * @date Last reviewed 2021-06-07
 */
class DirectoryNotFound extends \RuntimeException
{
    public $path; ///< Path that was not found

    /**
     * @param $path @copybrief $path
     *
     * @param $message If $message starts with a ';', it is appended to the
     *  generated message, otherwise it replaces the generated one.
     */
    public function __construct(
        string $path,
        string $message = '',
        int $code = 0,
        \Exception $previous = null
    ) {
        $this->path = $path;

        if (!$message || $message[0] == ';') {
            $message = "Directory \"$path\" not found$message";
        }

        parent::__construct($message, $code, $previous);
    }
}
