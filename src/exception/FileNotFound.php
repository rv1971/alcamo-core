<?php

namespace alcamo\exception;

/**
 * @brief Exception thrown when a file was not found
 *
 * @date Last reviewed 2021-06-07
 */
class FileNotFound extends FileException
{
    /**
     * @brief Places where the file was searched for (converatble to string),
     * or `null`
     */
    public $places;

    /**
     * @param $filename @copybrief FileException::$filename
     *
     * @param $places @copybrief $places
     *
     * @param $message If $message starts with a ';', it is appended to the
     *  generated message, otherwise it replaces the generated one.
     */
    public function __construct(
        string $filename,
        ?string $places = null,
        string $message = '',
        int $code = 0,
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
