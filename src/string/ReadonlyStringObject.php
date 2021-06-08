<?php

namespace alcamo\string;

use alcamo\collection\PreventWriteArrayAccessTrait;

/**
 * @brief Class that behaves much like a readonly string
 *
 * @date Last reviewed 2021-06-08
 */
class ReadonlyStringObject extends StringObject
{
    use PreventWriteArrayAccessTrait;
}
