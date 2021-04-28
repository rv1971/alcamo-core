<?php

namespace alcamo\integer;

use alcamo\exception\{OutOfRange, SyntaxError};

/**
 * @brief Range of integers.
 *
 * @invariant @ref $min_ is a nonnegative integer.
 *
 * @invariant @ref $max_ is either `null` or an integer greater than or equal
 * to @ref $min_.
 */
class NonNegativeRange
{
    /**
     * @brief Create from string.
     *
     * Supports an empty string or the syntax `<min> [- [<max>]]`.
     */
    public static function newFromString(string $str): self
    {
        $str = trim($str);

        if ($str == '') {
            return new static();
        }

        /** @throw SyntaxError if the input is not well-formed. */
        if (
            !preg_match(
                '/^(\d+)(\s*-\s*(\d+)?)?$/',
                $str,
                $matches,
                PREG_UNMATCHED_AS_NULL
            )
        ) {
            throw new SyntaxError($str, null, '; not a valid length range');
        }

        $min = intval($matches[1]);

        $max = isset($matches[3])
            ? intval($matches[3])
            : (isset($matches[2]) ? null : $min);

        return new static($min, $max);
    }

    private $min_; ///< Minimum length (nonnegative integer)
    private $max_; ///< Maximum length (nonnegative integer or null)

    public function __construct(?int $min = null, ?int $max = null)
    {
        /** @throw OutOfRange if `$min` is less than zero. */
        if ($min < 0) {
            throw new OutOfRange($min, 0);
        }

        /** @throw InvalidArgument if `$max` is less than $min. */
        if (isset($max) && $max < $min) {
            throw new OutOfRange($max, $min);
        }

        $this->min_ = (int)$min;
        $this->max_ = $max;
    }

    public function getMin(): int
    {
        return $this->min_;
    }

    public function getMax(): ?int
    {
        return $this->max_;
    }

    /// Convert to \<min>-\<max> representation.
    public function __toString()
    {
        /** Return empty string if no limits are set. */
        if (!$this->min_ && !isset($this->max_)) {
            return '';
        }

        /** Otherwise, return a number if the limits are equal. */
        if ($this->min_ === $this->max_) {
            return (string)$this->min_;
        }

        /** Otherwise, return \<min>-\<max>. */
        return "{$this->min_}-{$this->max_}";
    }

    /// Whether any bounds are defined at all.
    public function isBounded(): bool
    {
        return $this->min_ || isset($this->max_);
    }

    /// Whether the range is one exact length.
    public function isExact(): bool
    {
        return $this->min_ === $this->max_;
    }

    /// Whether $val is contained in the defined range.
    public function contains(int $val): bool
    {
        return $this->min_ <= $val
            && (!isset($this->max_) || $val <= $this->max_);
    }
}
