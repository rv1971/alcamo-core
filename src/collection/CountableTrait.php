<?php

namespace alcamo\collection;

/**
 * @brief Provide the Countable interface accessing a class property $data_
 *
 * @attention Any class using this trait must provide a class property $data_
 * which contains an array or a
 * [Countable interface](https://www.php.net/manual/en/class.countable).
 *
 * @sa [Countable interface](https://www.php.net/manual/en/class.countable)
 *
 * @date Last reviewed 2021-06-08
 */
trait CountableTrait
{
    public function count()
    {
        return count($this->data_);
    }
}
