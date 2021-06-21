<?php

namespace alcamo\rdfa;

use alcamo\iana\MediaType;

/**
 * @brief dc:format RDFa statement
 *
 * @sa [dc:format](http://purl.org/dc/terms/format).
 *
 * @date Last reviewed 2021-06-21
 */
class DcFormat extends AbstractStmt
{
    use NoHtmlTrait;

    public const PROPERTY_CURIE = 'dc:format';
    public const HTTP_HEADER    = 'Content-Type';
    public const OBJECT_CLASS   = MediaType::class;

    public function __construct(MediaType $mediaType)
    {
        parent::__construct($mediaType, false);
    }
}
