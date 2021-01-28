<?php

namespace alcamo\rdfa;

use alcamo\ietf\Lang;

/**
 * @sa [dc:language](http://purl.org/dc/terms/language).
 */
class DcLanguage extends AbstractStmt
{
    public const PROPERTY     = 'dc:language';
    public const HTTP_HEADER  = 'Content-Language';
    public const OBJECT_CLASS = Lang::class;

    public function __construct(Lang $lang)
    {
        parent::__construct($lang, false);
    }
}
