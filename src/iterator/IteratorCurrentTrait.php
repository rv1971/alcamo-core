<?php

namespace alcamo\iterator;

/// Trait with $current_ and related common members of Iterator implementations.
trait IteratorCurrentTrait
{
    private $currentKey_; ///< Key of current element.
    private $current_;    ///< Current element.

    public function current()
    {
        return $this->current_;
    }

    public function key()
    {
        return $this->currentKey_;
    }

    public function valid()
    {
        return isset($this->current_);
    }

    /// Call rewind() and return current(), just as PHP's built-in reset().
    public function reset()
    {
        $this->rewind();

        return $this->current_ ?? false;
    }
}
