<?php

namespace alcamo\collection;

/**
 * @brief Provide the Iterator interface accessing a class property $data_
 *
 * @attention Any class using this trait must provide a class property $data_
 * which must contain an array.
 *
 * @sa [Iterator interface](https://www.php.net/manual/en/class.iterator)
 *
 * @date Last reviewed 2021-06-08
 */
trait ArrayIteratorTrait
{
    public function rewind()
    {
        reset($this->data_);
    }

    public function current()
    {
        return current($this->data_);
    }

    public function key()
    {
        return key($this->data_);
    }

    public function next()
    {
        next($this->data_);
    }

    public function valid()
    {
        return $this->key() !== null;
    }

    /// Return the first value, or `null` if $data_ is empty
    public function first()
    {
        return $this->data_
            ? $this->data_[array_key_first($this->data_)]
            : null;
    }

    /// Return the last value, or `null` if $data_ is empty
    public function last()
    {
        return $this->data_
            ? $this->data_[array_key_last($this->data_)]
            : null;
    }
}
