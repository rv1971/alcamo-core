<?php

namespace alcamo\rdfa;

use alcamo\xml_creation\Nodes;

/**
 * @sa [Content-Disposition](http://tools.ietf.org/html/rfc2616#section-19.5.1)
 */
class HeaderContentDisposition extends AbstractStmt
{
    use LiteralContentTrait;
    use NoHtmlTrait;

    public const PROPERTY    = 'header:content-disposition';
    public const HTTP_HEADER = 'Content-Disposition';

    public function toHttpHeaders(): ?array
    {
        return [ static::HTTP_HEADER => "attachment; filename=\"$this\"" ];
    }
}