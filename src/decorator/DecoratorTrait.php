<?php

namespace alcamo\decorator;

/**
 * @brief Decorator pattern.
 *
 * The various interfaces must be written down explicitely, otherwise PHP does
 * not recognize that they are implemented.
 */
trait DecoratorTrait
{
    protected $handler_; ///< Handler object.

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
