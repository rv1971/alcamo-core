<?php

namespace alcamo\dom\extended;

use alcamo\dom\Element as BaseElement;

class Element extends BaseElement implements \ArrayAccess
{
    use AttrArrayAccessTrait;
    use HasLangTrait;
    use RegisteredNodeTrait;
}
