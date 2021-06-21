<?php

namespace alcamo\rdfa;

/**
 * @brief dc:modified RDFa statement
 *
 * @sa [dc:modified](http://purl.org/dc/terms/modified).
 *
 * @date Last reviewed 2021-06-21
 */
class DcModified extends AbstractDateTimeContentStmt
{
    public const PROPERTY_CURIE = 'dc:modified';
    public const HTTP_HEADER    = 'Last-Modified';

    public function toHttpHeaders(): array
    {
        return [ static::HTTP_HEADER => [ $this->format('r') ] ];
    }
}
