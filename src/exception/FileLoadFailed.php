<?php

namespace alcamo\exception;

/// File load failed even though file probably exists
class FileLoadFailed extends FileException
{
    /** If $message starts with a ';', it is appended to the generated message,
     *  otherwise it replaces the generated one. */
    public function __construct(
        $filename,
        $message = null,
        $code = 0,
        \Exception $previous = null
    ) {
        if (!$message || $message[0] == ';') {
            $message =
                "Failed to load file \"$filename\""
                . $message;
        }

        parent::__construct($filename, $message, $code, $previous);
    }
}
