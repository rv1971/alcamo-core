<?php

namespace alcamo\rdfa;

/**
 * @brief charset RDFa statement
 *
 * @sa [\<meta> charset attribute](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/meta#attr-charset)
 *
 * @date Last reviewed 2021-06-21
 */
class MetaCharset extends AbstractStmt
{
    use LiteralContentTrait;
    use NoPrefixMapTrait;

    public const PROPERTY_CURIE = 'meta:charset';

    public function toHtmlAttrs(): ?array
    {
        return [ 'charset' => (string)$this ];
    }
}
