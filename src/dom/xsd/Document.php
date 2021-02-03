<?php

namespace alcamo\dom\xsd;

use alcamo\dom\extended\Document as BaseDocument;

class Document extends BaseDocument
{
    public const NODE_CLASS =
        [
            'DOMAttr'    => Attr::class,
            'DOMElement' => Element::class
        ]
        + parent::NODE_CLASS;
}
