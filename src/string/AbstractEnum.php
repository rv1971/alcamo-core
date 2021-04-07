<?php

namespace alcamo\string;

use alcamo\exception\InvalidEnumerator;

/// String garanteed to be one of static::VALUES
abstract class AbstractEnum extends ReadonlyStringObject
{
    public const VALUES = [];

    public function __construct(string $text)
    {
        if (!in_array($text, static::VALUES)) {
            throw new InvalidEnumerator($text, static::VALUES);
        }

        parent::__construct($text);
    }
}
