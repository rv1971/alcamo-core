<?php

namespace alcamo\dom\psvi;

use alcamo\dom\extended\Element as BaseElement;
use alcamo\dom\schema\component\AbstractType;

class Element extends BaseElement
{
    private $type_;  ///< AbstractType

    public function getType(): AbstractType
    {
        if (!isset($this->type_)) {
            $this->type_ =
                $this->ownerDocument->getSchema()->lookupElementType($this);
        }

        return $this->type_;
    }
}
