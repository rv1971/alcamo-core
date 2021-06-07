<?php

namespace alcamo\exception;

/**
 * @brief Exception related to a file
 *
 * @date Last reviewed 2021-06-07
 */
class FileException extends \RuntimeException
{
    public $filename; ///< Name of file that triggered the exception

    /**
     * @param $filename @copybrief $filename
     */
    public function __construct(
        string $filename,
        string $message = '',
        int $code = 0,
        \Exception $previous = null
    ) {
        $this->filename = $filename;

        parent::__construct($message, $code, $previous);
    }
}
