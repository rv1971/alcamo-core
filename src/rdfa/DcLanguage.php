<?php

namespace alcamo\rdfa;

use alcamo\ietf\Lang;

/**
 * @brief dc:language RDFa statement
 *
 * @sa [dc:language](http://purl.org/dc/terms/language).
 *
 * @date Last reviewed 2021-06-21
 */
class DcLanguage extends AbstractStmt
{
    public const PROPERTY_CURIE = 'dc:language';
    public const HTTP_HEADER    = 'Content-Language';
    public const OBJECT_CLASS   = Lang::class;

    public function __construct(Lang $lang)
    {
        parent::__construct($lang, false);
    }
}
