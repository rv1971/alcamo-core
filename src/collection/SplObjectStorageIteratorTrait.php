<?php

namespace alcamo\collection;

/**
 * @brief Provide the Iterator interface by accessing a class property $data_
 *
 * @attention Any class using this trait must provide a class property $data_
 * which must contain an SplObjectStorage.
 *
 * @note Since this trait uses CloneTrait, a clone of an object using
 * this trait is iterated independently of the original object.
 *
 * Iteration over this class works as one would expect, [unlike
 * SplObjectStorage](https://bugs.php.net/bug.php?id=49967).
 *
 * @sa [Iterator interface](https://www.php.net/manual/en/class.iterator)
 * @sa [SplObjectStorage class](https://www.php.net/manual/en/class.splobjectstorage)
 */
trait SplObjectStorageIteratorTrait
{
    use CloneTrait;

    public function rewind()
    {
        $this->data_->rewind();
    }

    public function current()
    {
        return $this->data_->getInfo();
    }

    public function key()
    {
        return $this->data_->current();
    }

    public function next()
    {
        $this->data_->next();
    }

    public function valid()
    {
        return $this->data_->valid();
    }
}
