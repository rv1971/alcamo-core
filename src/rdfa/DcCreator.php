<?php

namespace alcamo\rdfa;

use alcamo\html_creation\element\Creator;

/**
 * @brief dc:creator RDFa statement
 *
 * @sa [dc:creator](http://purl.org/dc/terms/creator)
 *
 * @date Last reviewed 2021-06-21
 */
class DcCreator extends AbstractStmt
{
    use LiteralContentOrLinkTrait;

    public const PROPERTY_CURIE = 'dc:creator';
    public const META_NAME      = 'author';
    public const LINK_REL       = self::META_NAME;
}
