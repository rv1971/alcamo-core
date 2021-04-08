<?php

namespace alcamo\binary_data;

use alcamo\exception\{OutOfRange, SyntaxError};

/**
 * @brief Class representing a compressed BCD.
 *
 * Implemented as an uppercase hex-string so that single digits can be
 * accessed through the ArrayAccess interface and the number of digits
 * can be obtained via count().
 */
class CompressedBcd extends HexString
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
        string $value,
        ?int $minDigits = null,
        ?bool $allowOdd = null
    ): self {
        $digits = max(strlen($value), $minDigits);

        if (!$allowOdd) {
            $digits = ($digits + 1) & ~1;
        }

        return new static(str_pad($value, $digits, 'F', STR_PAD_RIGHT));
    }

    /// Create from numeric string that may contain whitespace.
    public function __construct(string $text)
    {
        $text = strtoupper(preg_replace('/\s+/', '', $text));

        $bareContent = rtrim($text, 'F');

        if (strspn($bareContent, '0123456789') != strlen($bareContent)) {
            throw new SyntaxError(
                $text,
                strspn($text, '0123456789'),
                '; not a valid compressed BCD literal'
            );
        }

        parent::__construct($text);
    }

    public function pad(?int $minLength = null, ?bool $allowOdd = null)
    {
        if (!$allowOdd) {
            $minLength = max($minLength, count($this));

            if ($minLength & 1) {
                $minLength++;
            }
        }

        $this->text_ = str_pad($this->text_, $minLength, 'F');
    }
}
