<?php

namespace alcamo\rdfa;

class MetaCharset extends AbstractStmt
{
    use LiteralContentTrait;
    use NoPrefixBindingTrait;

    public const PROPERTY_CURIE = 'meta:charset';

    public function toHtmlAttrs(): ?array
    {
        return [ 'charset' => (string)$this ];
    }
}
