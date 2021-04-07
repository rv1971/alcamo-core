<?php

namespace alcamo\binary_data;

use alcamo\exception\SyntaxError;

/// Hexadecimal string with an even number of digits.
class EvenHexString extends HexString
{
    /// Create from hex string that may contain whitespace.
    public function __construct(string $text)
    {
        $text = preg_replace('/\s+/', '', $text);

        /** @throw if $text does not have an even number of digits. */
        if (strlen($text) & 1) {
            throw new SyntaxError(
                $text,
                0,
                '; not an even number of hex digits'
            );
        }

        parent::__construct($text);
    }
}
