<?php

namespace alcamo\string;

use alcamo\exception\InvalidEnumerator;

/**
 * @brief Object that represents a readonly string garanteed to be one of
 * @ref VALUES
 *
 * @attention Any derived classes must define a public constant
 * VALUES containing the valid values.
 *
 * @date Last reviewed 2021-06-08
 */
abstract class AbstractEnum extends ReadonlyStringObject
{
    public const VALUES = [];

    public function __construct(string $value)
    {
        if (!in_array($value, static::VALUES)) {
            /** @throw alcamo::exception::InvalidEnumerator if $value is not
             *  in @ref VALUES. */
            throw new InvalidEnumerator($value, static::VALUES);
        }

        parent::__construct($value);
    }
}
