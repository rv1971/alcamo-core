<?php

namespace alcamo\string;

use alcamo\collection\PreventWriteArrayAccessTrait;

class ReadonlyStringObject extends StringObject
{
    use PreventWriteArrayAccessTrait;
}
