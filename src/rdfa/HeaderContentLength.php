<?php

namespace alcamo\rdfa;

/**
 * @brief content-length RDFa statement
 *
 * @sa [Content-Length](http://tools.ietf.org/html/rfc2616#section-14.13)
 *
 * @date Last reviewed 2021-06-21
 */
class HeaderContentLength extends AbstractStmt
{
    use LiteralContentTrait;
    use NoHtmlTrait;
    use NoPrefixMapTrait;

    public const PROPERTY_CURIE = 'header:content-length';
    public const HTTP_HEADER    = 'Content-Length';

    /// Create as size of file
    public static function newFromFilename($filename)
    {
        return new static(filesize($filename));
    }
}
