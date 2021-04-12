<?php

namespace alcamo\binary_data;

use alcamo\string\StringObject;
use alcamo\exception\SyntaxError;

class HexString extends StringObject implements \ArrayAccess, \Countable
{
    public static function newFromBinaryString(string $data): self
    {
        return new static(bin2hex($data));
    }

    /// Create from hex string that may contain whitespace.
    public function __construct(string $text)
    {
        $text = strtoupper(preg_replace('/\s+/', '', $text));

        if ($text != '' && !ctype_xdigit($text)) {
            throw new SyntaxError($text, null, '; not a valid hex string');
        }

        parent::__construct($text);
    }

    public function offsetSet($offset, $value)
    {
        $value = strtoupper($value);

        if (!ctype_xdigit($value) || strlen($value) > 1) {
            throw new SyntaxError($value, 0, '; not a valid hex digit');
        }

        parent::offsetSet($offset, $value);
    }

    public function toBinaryString(): BinaryString
    {
        return new BinaryString(hex2bin($this->text_));
    }
}
