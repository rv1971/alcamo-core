<?php

namespace alcamo\rdfa;

/**
 * @brief content-disposition RDFa statement
 *
 * @sa [Content-Disposition](http://tools.ietf.org/html/rfc2616#section-19.5.1)
 *
 * If used, the statement object is the filename suggested for saving the
 * attachment.
 *
 * @date Last reviewed 2021-06-21
 */
class HeaderContentDisposition extends AbstractStmt
{
    use LiteralContentTrait;
    use NoHtmlTrait;
    use NoPrefixMapTrait;

    public const PROPERTY_CURIE = 'header:content-disposition';
    public const HTTP_HEADER    = 'Content-Disposition';

    public function toHttpHeaders(): ?array
    {
        return [ static::HTTP_HEADER => [ "attachment; filename=\"$this\"" ] ];
    }
}
