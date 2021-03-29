<?php

namespace alcamo\exception;

/// Directory not found
class DirectoryNotFound extends \RuntimeException
{
    public $path;

    /** If $message starts with a ';', it is appended to the generated message,
     *  otherwise it replaces the generated one. */
    public function __construct(
        string $path,
        string $message = '',
        int $code = 0,
        \Exception $previous = null
    ) {
        if (!$message || $message[0] == ';') {
            $message = "Directory \"$path\" not found$message";
        }

        $this->path = $path;
        parent::__construct($message, $code, $previous);
    }
}
