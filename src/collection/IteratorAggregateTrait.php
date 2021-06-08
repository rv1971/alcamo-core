<?php

namespace alcamo\collection;

/**
 * @brief Provide the IteratorAggregate interface accessing a class property
 * $data_
 *
 * @attention Any class using this trait must provide a class property $data_
 * which must contain an array or a
 * [Traversable[(https://www.php.net/manual/en/class.traversable)
 *
 * @sa [IteratorAggregate interface](https://www.php.net/manual/en/class.iteratoraggregate)
 *
 * @date Last reviewed 2021-06-08
 */
trait IteratorAggregateTrait
{
    public function getIterator(): \Traversable
    {
        return $this->data_;
    }
}
