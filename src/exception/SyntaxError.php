<?php

namespace alcamo\exception;

class SyntaxError extends \DomainException
{
    public $text;
    public $offset;

    /** If $message starts with a ';', it is appended to the generated message,
     *  otherwise it replaces the generated one. */
    public function __construct(
        string $text,
        ?int $offset = null,
        $message = null,
        $code = 0,
        \Exception $previous = null
    ) {
        $this->text = $text;
        $this->offset = $offset;

        if (!$message || $message[0] == ';') {
            $shortText =
                strlen($text) <= 40 ? $text : (substr($text, 0, 40) . '...');

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
