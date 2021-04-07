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

        if (!preg_match('/^[0-9A-F]*$/', $text)) {
            throw new SyntaxError($text, null, '; not a valid hex string');
        }

        parent::__construct($text);
    }

    public function offsetSet($offset, $value)
    {
        $value = strtoupper($value);

        if (strtr($value, '123456789ABCDEF', '000000000000000') != '0') {
            throw new SyntaxError($value, 0, '; not a valid hex digit');
        }

        parent::offsetSet($offset, $value);
    }

    public function toBinaryString(): BinaryString
    {
        return new BinaryString(hex2bin($this->text_));
    }
}
