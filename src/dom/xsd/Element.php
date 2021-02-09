<?php

namespace alcamo\dom\xsd;

use alcamo\dom\extended\Element as BaseElement;
use alcamo\xml\XName;

class Element extends BaseElement
{
    private $xName_ = false; ///< ?XName

    public function getComponentXName(): ?XName
    {
        if ($this->xName_ === false) {
            /* Since offsetGet() is called, conservation of this derived
             * object is already ensured. */

            if (isset($this['ref'])) {
                $this->xName_ = $this['ref'];
            } elseif (isset($this['name'])) {
                $this->xName_ = new XName(
                    $this->ownerDocument->documentElement['targetNamespace'],
                    $this['name']
                );
            } else {
                $this->xName_ = null;
            }
        }

        return $this->xName_;
    }
}
