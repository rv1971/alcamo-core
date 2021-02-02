<?php

namespace alcamo\dom\extended;

use alcamo\dom\Document as BaseDocument;

class Document extends BaseDocument
{
    use NodeRegistryTrait;

    public const NODE_CLASS =
        [
            'DOMAttr'    => Attr::class,
            'DOMElement' => Element::class
        ]
        + parent::NODE_CLASS;
}
