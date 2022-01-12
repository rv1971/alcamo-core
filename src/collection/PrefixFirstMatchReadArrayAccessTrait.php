<?php

namespace alcamo\collection;

/**
 * @brief Read the first array item mathing an initial portion of an offset
 *
 * This implementation of the read methods of ArrayAccess look for the *first*
 * array element whose key matches an initial substring of $offset. This
 * implies that order of the underlying array may be significant.
 *
 * @attention Any class using this trait must provide a class property $data_
 * which must contain an array or an
 * [ArrayAccess](https://www.php.net/manual/en/class.arrayaccess).
 *
 * @sa [ArrayAccess interface](https://www.php.net/manual/en/class.arrayaccess)
 */
trait PrefixFirstMatchReadArrayAccessTrait
{
    public function offsetExists($offset)
    {
        foreach ($this->data_ as $key => $value) {
            /* strncmp() is unsuitable because it would return true also when
             * $offset were a proper intial substring of $key. */
            if (substr($offset, 0, strlen($key)) == $key) {
                return true;
            }
        }

        return false;
    }

    public function offsetGet($offset)
    {
        foreach ($this->data_ as $key => $value) {
            if (substr($offset, 0, strlen($key)) == $key) {
                return $value;
            }
        }

        return null;
    }
}
