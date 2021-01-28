<?php

namespace alcamo\exception;

/// File not found
class FileNotFound extends FileException
{
    public $places;

    /** If $message starts with a ';', it is appended to the generated message,
     *  otherwise it replaces the generated one. */
    public function __construct(
        $filename,
        ?string $places = null,
        $message = null,
        $code = 0,
        \Exception $previous = null
    ) {
        $this->places = $places;

        if (!$message || $message[0] == ';') {
            $message =
                "File \"$filename\" not found"
                . (isset($places) ? " in \"$places\"" : '')
                . $message;
        }

        parent::__construct($filename, $message, $code, $previous);
    }
}
