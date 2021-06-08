<?php

namespace alcamo\collection;

/**
 * @brief Provide the writing methods of the ArrayAccess interface accessing
 * a class property $data_
 *
 * @attention Any class using this trait must provide a class property $data_
 * which must contain an array or an
 * [ArrayAccess](https://www.php.net/manual/en/class.arrayaccess).
 *
 * @sa [ArrayAccess interface](https://www.php.net/manual/en/class.arrayaccess)
 *
 * @date Last reviewed 2021-06-08
 */
trait WriteArrayAccessTrait
{
    public function offsetSet($offset, $value)
    {
        $this->data_[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->data_[$offset]);
    }
}
