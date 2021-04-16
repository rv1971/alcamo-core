<?php

namespace alcamo\binary_data;

use alcamo\exception\{OutOfRange, Unsupported};

/// Array of bytes to represent binary content.
class BinaryString implements \ArrayAccess, \Countable
{
    public static function newFromInt(int $value, int $minBytes = null): self
    {
        switch (true) {
            case $value <= 0xff:
                $result = chr($value);
                break;

            case $value <= 0xffff:
                $result = pack('n', $value);
                break;

            case $value <= 0xffffffff:
                $result = ltrim(pack('N', $value), "\x00");
                break;

            default:
                $result = ltrim(pack('J', $value), "\x00");
                break;
        }

        return new static(
            isset($minBytes)
            ? str_pad($result, $minBytes, "\00", STR_PAD_LEFT)
            : $result
        );
    }

    protected $data_; ///< Binary string.

    /// Create from hex string which may contain whitespace
    public static function newFromHex(string $hex)
    {
        return new static(hex2bin(preg_replace('/\s+/', '', $hex)));
    }

    /// Create from binary string.
    public function __construct(string $data = null)
    {
        $this->data_ = (string)$data;
    }

    public function getData(): string
    {
        return $this->data_;
    }

    /// Represent as uppercase hex string.
    public function __toString()
    {
        return strtoupper(bin2hex($this->data_));
    }

    /// Return number of bytes.
    public function count()
    {
        return strlen($this->data_);
    }

    /// Whether a byte offset exists.
    public function offsetExists($offset)
    {
        return isset($this->data_[$offset]);
    }

    /// Get the byte at an offset as an integer, *not as a character*
    public function offsetGet($offset)
    {
        return ord($this->data_[$offset]);
    }

    public function offsetSet($offset, $value)
    {
        if (!isset($this->data_[$offset])) {
            /** @throw OutOfRange if $offset outside of string */
            throw new OutOfRange(
                $offset,
                0,
                strlen($this->data_) - 1,
                '; offset outside of given binary string'
            );
        }

        $value = (int)$value;

        OutOfRange::throwIfOutside(
            $value,
            0,
            0xff,
            '; value does not represent a byte'
        );

        $this->data_[$offset] = chr($value);
    }

    // Unsetting is not possible
    public function offsetUnset($offset)
    {
        throw new Unsupported('Unsetting bytes in a binary string');
    }

    /* == operations == */

    /// Get as integer, if possible.
    public function toInt(): ?int
    {
        switch (strlen($this->data_)) {
            case 1:
                return ord($this->data_);

            case 2:
                return unpack('n', $this->data_)[1];

            case 3:
                return unpack('N', "\x00$this->data_")[1];

            case 4:
                return unpack('N', $this->data_)[1];

            case 5:
                return unpack('J', "\x00\x00\x00$this->data_")[1];

            case 6:
                return unpack('J', "\x00\x00$this->data_")[1];

            case 7:
                return unpack('J', "\x00$this->data_")[1];

            case 8:
                return unpack('J', $this->data_)[1];

            default:
                return null;
        }
    }

    /// Perform bitwise and.
    public function bitwiseAnd(self $binString): self
    {
        $length = max(count($this), count($binString));

        /** Left-pad with zeros to obtain two strigns of equal length. */
        $op1 = str_pad($this->data_, $length, "\x00", STR_PAD_LEFT);
        $op2 = str_pad($binString->data_, $length, "\x00", STR_PAD_LEFT);

        /** The result is as long as the longest operand. */
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= chr(ord($op1[$i]) & ord($op2[$i]));
        }

        return new self($result);
    }

    /// Perform bitwise or.
    public function bitwiseOr(self $binString): self
    {
        $length = max(count($this), count($binString));

        /** Left-pad with zeros to obtain two strigns of equal length. */
        $op1 = str_pad($this->data_, $length, "\x00", STR_PAD_LEFT);
        $op2 = str_pad($binString->data_, $length, "\x00", STR_PAD_LEFT);

        /** The result is as long as the longest operand. */
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= chr(ord($op1[$i]) | ord($op2[$i]));
        }

        return new self($result);
    }
}
