<?php

namespace alcamo\decorator;

/// Decorator pattern.
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
}
