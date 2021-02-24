<?php

namespace alcamo\rdfa;

use alcamo\html_creation\element\Creator;

/**
 * @sa [dc:creator](http://purl.org/dc/terms/creator).
 */
class DcCreator extends AbstractStmt
{
    use LiteralContentOrLinkTrait;

    public const PROPERTY = 'dc:creator';
    public const META_NAME = 'author';
    public const LINK_REL = self::META_NAME;
}
