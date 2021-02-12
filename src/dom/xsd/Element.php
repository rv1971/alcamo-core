<?php

namespace alcamo\dom\xsd;

use alcamo\dom\extended\Element as BaseElement;
use alcamo\xml\XName;

class Element extends BaseElement
{
    private $xComponentName_ = false; ///< ?XName

    public function getComponentXName(): ?XName
    {
        if ($this->xComponentName_ === false) {
            /* Since offsetGet() is called, conservation of this derived
             * object is already ensured. */

            if (isset($this['ref'])) {
                $this->xComponentName_ = $this['ref'];
            } elseif (isset($this['name'])) {
                $this->xComponentName_ = new XName(
                    $this->ownerDocument->documentElement['targetNamespace'],
                    $this['name']
                );
            } else {
                $this->xComponentName_ = null;
            }
        }

        return $this->xComponentName_;
    }
}
