<?php

namespace alcamo\binary_data;

use alcamo\exception\{OutOfRange, SyntaxError};

/**
 * @brief Class representing BCD-encoded data.
 *
 * Implemented as an uppercase hex-string so that single digits can be
 * accessed through the ArrayAccess interface and the number of digits
 * can be obtained via count().
 */
class Bcd extends HexString
{
  /**
   * @brief Create from integer.
   *
   * @param $value int Value to encode.
   *
   * @param $minDigits int Minimum length of the result in digits.
   *
   * @param $allowOdd bool Whether the result may have an odd number of
   * digits.
   */
    public static function newFromInt(
        int $value,
        ?int $minDigits = null,
        ?bool $allowOdd = null
    ): self {
        $data = (string)$value;

        $digits = max(strlen($data), $minDigits);

        if (!$allowOdd) {
            $digits = ($digits + 1) & ~1;
        }

        return new static(str_pad($data, $digits, '0', STR_PAD_LEFT));
    }

    /// Create from numeric string that may contain whitespace.
    public function __construct(string $text)
    {
        $text = preg_replace('/\s+/', '', $text);

        if (strspn($text, '0123456789') != strlen($text)) {
            throw new SyntaxError(
                $text,
                strspn($text, '0123456789'),
                '; not a valid integer literal'
            );
        }

        parent::__construct($text);
    }

    /// Get as integer, if possible.
    public function toInt(): int
    {
        if (!isset($this->text_[0])) {
            return 0;
        }

        if (is_int($this->text_ + 0)) {
            return (int)$this->text_;
        }

        throw new OutOfRange(
            $this,
            0,
            PHP_INT_MAX,
            '; unable to convert BCD to integer'
        );
    }

    public function pad(?int $minLength = null, ?bool $allowOdd = null)
    {
        if (!$allowOdd) {
            $minLength = max($minLength, count($this));

            if ($minLength & 1) {
                $minLength++;
            }
        }

        $this->text_ = str_pad($this->text_, $minLength, '0', STR_PAD_LEFT);
    }
}
