<?php

namespace alcamo\string;

use alcamo\exception\{OutOfRange, ReadonlyViolation};

class StringObject implements \ArrayAccess, \Countable
{
    protected $text_;

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

    /// Only offsets within the existing string may be modified
    public function offsetSet($offset, $value)
    {
        if (!isset($this->text_[$offset])) {
            throw new OutOfRange($offset, 0, strlen($this->text_) - 1);
        }

        $this->text_[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        throw new ReadonlyViolation(
            $this,
            __FUNCTION__,
            'Attempt to use ' . __CLASS__ . '::' . __FUNCTION__ . '()'
        );
    }
}
