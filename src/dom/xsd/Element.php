<?php

namespace alcamo\dom\xsd;

use alcamo\dom\extended\Element as BaseElement;
use alcamo\xml\XName;

class Element extends BaseElement
{
    private $uniqueName_; ///< XName object

    public function getUniqueName(): XName
    {
        if (!isset($this->uniqueName_)) {
            /* Since offsetGet() is called, conservation of this derived
             * objects is already ensured. */

            if (isset($this['ref'])) {
                $this->uniqueName_ = $this['ref'];
            } else {
                /** If the element does not have a `name` attribute, create
                 * one recursively by appending the position among siblings,
                 * counting from 1, to the unique name of the parent node. */
                $localName = isset($this['name'])
                    ? $this['name']
                    : ($this->parentNode->getUniqueName()->getLocalName()
                       . '/'
                       . ($this->evaluate('count(preceding-sibling::*)') + 1));

                $this->uniqueName_ = new XName(
                    $this->ownerDocument->documentElement['targetNamespace'],
                    $localName
                );
            }
        }

        return $this->uniqueName_;
    }
}
