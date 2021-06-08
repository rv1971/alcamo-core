<?php

namespace alcamo\string;

use alcamo\exception\{OutOfRange, ReadonlyViolation};

/**
 * @brief Class that behaves much like a string
 *
 * All positions are counted in bytes, not in characters. This makes a
 * difference for UTF-8 strings.
 *
 * @date Last reviewed 2021-06-08
 */
class StringObject implements \ArrayAccess, \Countable
{
    protected $text_; ///< The actual string

    public function __construct(string $text)
    {
        $this->text_ = $text;
    }

    public function __toString()
    {
        return $this->text_;
    }

    /* == Countable interface == */

    public function count()
    {
        return strlen($this->text_);
    }

    /* == ArrayAccess interface as for strings == */

    public function offsetExists($offset)
    {
        return isset($this->text_[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->text_[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if (!isset($this->text_[$offset])) {
            /** @throw alcamo::exception::OutOfRange when attempting to modify
             *  a position outside of the existing string. */
            throw new OutOfRange($offset, 0, strlen($this->text_) - 1);
        }

        $this->text_[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        /** @throw alcamo::exception::ReadonlyViolation in ever invocation
         *  unsetting single positions is not supported. */
        throw new ReadonlyViolation(
            $this,
            __FUNCTION__,
            'Attempt to use ' . __CLASS__ . '::' . __FUNCTION__ . '()'
        );
    }
}
