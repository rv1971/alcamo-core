<?php

namespace alcamo\exception;

/**
 * @brief Exception thrown when validation of structured data such as JSON or
 * XML failed.
 *
 * @date Last reviewed 2021-06-07
 */
class DataValidationFailed extends \RuntimeException
{
    public $data;     ///< Data that can be converted to string
    public $uri;      ///< URI where data was found, or `null`
    public $dataLine; ///< Line number, or `null`

    /**
     * @param $data @copybrief $data
     *
     * @param $uri @copybrief $uri
     *
     * @param $dataLine @copybrief $dataLine
     *
     * @param $message If $message starts with a ';', it is appended to the
     *  generated message, otherwise it replaces the generated one.
     */
    public function __construct(
        $data,
        $uri = null,
        $dataLine = null,
        $message = '',
        $code = 0,
        \Exception $previous = null
    ) {
        $this->data = $data;
        $this->uri = $uri;
        $this->dataLine = $dataLine;

        if (!$message || $message[0] == ';') {
            /** Display at most the first 40 characters of @ref $data. */
            $shortText =
                strlen($data) <= 40 ? $data : (substr($data, 0, 40) . '...');

            $message = "Failed to validate \"$shortText\""
                . (isset($uri) ? " at $uri" : '')
                . (isset($dataLine) ? ", line $dataLine" : '')
                . $message;
        }

        parent::__construct($message, $code, $previous);
    }
}
