<?php

namespace alcamo\rdfa;

/**
 * @brief dc:source RDFa statement
 *
 * @sa [dc:source](http://purl.org/dc/terms/source).
 *
 * @date Last reviewed 2021-06-21
 */
class DcSource extends AbstractStmt
{
    use ResourceObjectTrait;

    public const PROPERTY_CURIE = 'dc:source';
    public const HTTP_HEADER    = 'Link';
    public const LINK_REL       = 'canonical';

    public function toHttpHeaders(): array
    {
        return [
            static::HTTP_HEADER => [
                "<{$this}>; rel=\"" . static::LINK_REL . '"'
            ]
        ];
    }
}
