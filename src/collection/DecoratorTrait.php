<?php

namespace alcamo\collection;

/**
 * @brief Provide __call() accessing a class property $data_
 *
 * @attention Any class using this trait must provide a class property $data_
 * which must contain an object.
 *
 * @date Last reviewed 2021-06-08
 */
trait DecoratorTrait
{
    public function __call(string $name, array $params)
    {
        return call_user_func_array([ $this->data_, $name ], $params);
    }
}
