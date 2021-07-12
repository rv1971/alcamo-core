<?php

namespace alcamo\range;

/**
 * @namespace alcamo::range
 *
 * @brief Classes to model ranges
 */

/**
 * @brief Range of values, including lower and upper bound, if defined
 *
 * Most methods common to all kinds of ranges are not listed here because they
 * need different types depending on the range.
 */
interface RangeInterface
{
    public function __toString();

    /// Whether there is any lower or upper bound
    public function isDefined(): bool;

    /// Whether the range is one exact value
    public function isExactValue(): bool;
}
