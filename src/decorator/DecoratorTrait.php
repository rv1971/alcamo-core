<?php

namespace alcamo\decorator;

/**
 * @brief Provides a decorator for $handler_
 *
 * This includes the methods that implement
 * - [Countable](https://www.php.net/manual/en/class.countable)
 * - [Iterator](https://www.php.net/manual/en/class.iterator)
 * - [IteratorAggregate](https://www.php.net/manual/en/class.iteratoraggregate)
 * - [ArrayAccess](https://www.php.net/manual/en/class.arrayaccess)
 *
 * @ref $handler_ must contain an object that implements all those methods
 * which are actually used.
 *
 * These methods are written down explicitely, otherwise PHP would not
 * recognize that they are implemented, even though their implementation is
 * equivalent to the implicit use of the magic methods.
 *
 * @date Last reviewed 2021-06-08
 */
trait DecoratorTrait
{
    protected $handler_; ///< Handler object

    /**
     * @param $handler @copybrief $handler_
     */
    public function __construct($handler)
    {
        $this->handler_ = $handler;
    }

    public function __isset($name)
    {
        return isset($this->handler_->$name);
    }

    public function __unset($name)
    {
        unset($this->handler_->$name);
    }

    public function __get($name)
    {
        return $this->handler_->$name;
    }

    public function __set($name, $value)
    {
        $this->handler_->$name = $value;
    }

    public function __call($name, $params)
    {
        return call_user_func_array([ $this->handler_, $name ], $params);
    }

    /* == Countable interface == */

    public function count()
    {
        return $this->handler_->count();
    }

    /* == Iterator interface == */

    public function rewind()
    {
        return $this->handler_->rewind();
    }

    public function current()
    {
        return $this->handler_->current();
    }

    public function key()
    {
        return $this->handler_->key();
    }

    public function next()
    {
        return $this->handler_->next();
    }

    public function valid()
    {
        return $this->handler_->valid();
    }

    /* == IteratorAggregate interface == */

    public function getIterator()
    {
        return $this->handler_->getIterator();
    }

    /* == ArrayAccess interface == */

    public function offsetExists($offset)
    {
        return $this->handler_->offsetExists($offset);
    }

    public function offsetGet($offset)
    {
        return $this->handler_->offsetGet($offset);
    }

    public function offsetSet($offset, $value)
    {
        return $this->handler_->offsetSet($offset, $value);
    }

    public function offsetUnset($offset)
    {
        return $this->handler_->offsetUnset($offset);
    }
}
