<?php

namespace alcamo\collection;

// Decorator for a class property $data_
trait DecoratorTrait
{
    public function __call($name, $params)
    {
        return call_user_func_array([ $this->data_, $name ], $params);
    }
}
