<?php

namespace alcamo\collection;

/**
 * @brief Provide contains() accessing a class property $data_
 *
 * @attention Any class using this trait must provide a class property $data_
 * which contains an object that has a method contains().
 */
trait ContainsTrait
{
    public function contains($value): bool
    {
        return $this->data_->contains($value);
    }
}
