<?php

namespace alcamo\dom\extended;

use alcamo\dom\Attr as BaseAttr;

class Attr extends BaseAttr
{
    use RegisteredNodeTrait;

    /// To be redefined in child classes with something more sophisticated
    public function getValue()
    {
        return $this->value;
    }
}
