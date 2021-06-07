<?php

namespace alcamo\exception;

/**
 * @brief Exception thrown when a syntax error occurred
 *
 * @date Last reviewed 2021-06-07
 */
class SyntaxError extends \DomainException
{
    public $text;   ///< Text the syntax error occured in
    public $offset; ///< Offset in \ref $text, or `null`

    /**
     * @param $text @copybrief $text
     *
     * @param $offset @copybrief $offset
     *
     * @param $message If $message starts with a ';', it is appended to the
     *  generated message, otherwise it replaces the generated one.
     */
    public function __construct(
        string $text,
        ?int $offset = null,
        string $message = '',
        int $code = 0,
        \Exception $previous = null
    ) {
        $this->text = $text;
        $this->offset = $offset;

        if (!$message || $message[0] == ';') {
            /** Display at most the first 40 characters of @ref $text. */
            $shortText =
                strlen($text) <= 40 ? $text : (substr($text, 0, 40) . '...');

            /** If @ref $offset is given, display at most the first 40
             *  characters of offeding text. */
            if (isset($offset)) {
                $shortOffendingText =
                    strlen($text) <= $offset + 10
                    ? substr($text, $offset)
                    : (substr($text, $offset, 10) . '...');
            }

            $message =
                "Syntax error in \"$shortText\""
                . (isset($offset)
                   ? (" at $offset: \"$shortOffendingText\"")
                   : '')
                . $message;
        }

        parent::__construct($message, $code, $previous);
    }
}
