<?php

namespace alcamo\dom\xsd;

use alcamo\dom\DocumentFactoryInterface;
use alcamo\dom\extended\{Document as BaseDocument, DocumentFactory};

class Document extends BaseDocument
{
    public const NODE_CLASS =
        [
            'DOMAttr'    => Attr::class,
            'DOMElement' => Element::class
        ]
        + parent::NODE_CLASS;

    public function getDocumentFactory(): DocumentFactoryInterface
    {
        return new DocumentFactory();
    }
}
