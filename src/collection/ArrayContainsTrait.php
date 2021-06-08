<?php

namespace alcamo\collection;

/**
 * @brief Provide contains() accessing a class property $data_
 *
 * @attention Any class using this trait must provide a class property $data_
 * which contains an array.
 *
 * @date Last reviewed 2021-06-08
 */
trait ArrayContainsTrait
{
    public function contains($value): bool
    {
        return in_array($value, $this->data_, true);
    }
}
