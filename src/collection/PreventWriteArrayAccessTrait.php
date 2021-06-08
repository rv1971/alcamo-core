<?php

namespace alcamo\collection;

use alcamo\exception\ReadonlyViolation;

/**
 * @brief Provide writing methods of the ArrayAccess interface that always
 * throw
 *
 * Together with ReadArrayAccessTrait or any other implementation of the
 * reading methods of the ArrayAccess, this can be used to build a class that
 * supports ArrayAccess in a readonly way.
 *
 * @sa [ArrayAccess interface](https://www.php.net/manual/en/class.arrayaccess)
 *
 * @date Last reviewed 2021-06-08
 */
trait PreventWriteArrayAccessTrait
{
    public function offsetSet($offset, $value)
    {
        /** @throw alcamo::exception::ReadonlyViolation in every
         *  invocation. */
        throw new ReadonlyViolation();
    }

    public function offsetUnset($offset)
    {
        /** @throw alcamo::exception::ReadonlyViolation in every
         *  invocation. */
        throw new ReadonlyViolation();
    }
}
