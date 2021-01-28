<?php

namespace alcamo\rdfa;

use alcamo\exception\InvalidEnumerator;

/// Statement whose object is an enumerator
abstract class AbstractEnumeratorStmt extends AbstractStmt
{
    public function __construct($value)
    {
        if (!in_array($value, static::VALUES)) {
          /** @throw InvalidEnumerator if the $value is not a valid enumerator. */
            throw new InvalidEnumerator($value, static::VALUES);
        }

        parent::__construct($value, false);
    }
}
