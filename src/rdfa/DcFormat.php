<?php

namespace alcamo\rdfa;

use alcamo\iana\MediaType;

/**
 * @sa [dc:format](http://purl.org/dc/terms/format).
 */
class DcFormat extends AbstractStmt
{
    public const PROPERTY     = 'dc:format';
    public const HTTP_HEADER  = 'Content-Type';
    public const OBJECT_CLASS = MediaType::class;

    public function __construct(MediaType $mediaType)
    {
        parent::__construct($mediaType, false);
    }
}
