<?php

namespace alcamo\rdfa;

use alcamo\exception\InvalidEnumerator;

/**
 * @brief RDFa statement whose object is an enumerator
 *
 * @attention Each derived class must define a class constant VALUES which
 * must be an array of legal values.
 *
 * An enumerator is always a literal value, not a resource.
 *
 * @date Last reviewed 2021-06-18
 */
abstract class AbstractEnumeratorStmt extends AbstractStmt
{
    public function __construct($value)
    {
        if (!in_array($value, static::VALUES)) {
            /** @throw alcamo::exception::InvalidEnumerator if the $value is
             *  not a valid enumerator. */
            throw new InvalidEnumerator($value, static::VALUES);
        }

        parent::__construct($value, false);
    }
}
