<?php

namespace alcamo\exception;

/**
 * @brief Exception thrown when a file could not be loaded even though it
 * probably exists.
 *
 * @date Last reviewed 2021-06-07
 */
class FileLoadFailed extends FileException
{
    /**
     * @param $filename @copybrief FileException::$filename
     *
     * @param $message If $message starts with a ';', it is appended to the
     *  generated message, otherwise it replaces the generated one.
     */
    public function __construct(
        string $filename,
        string $message = '',
        int $code = 0,
        \Exception $previous = null
    ) {
        if (!$message || $message[0] == ';') {
            $message = "Failed to load file \"$filename\"$message";
        }

        parent::__construct($filename, $message, $code, $previous);
    }
}
