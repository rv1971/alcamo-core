<?php

namespace alcamo\rdfa;

/**
 * @sa [dc:abstract](http://purl.org/dc/terms/abstract).
 */
class DcAbstract extends AbstractStmt
{
    use LiteralContentTrait;

    public const PROPERTY_CURIE = 'dc:abstract';
    public const META_NAME      = 'description';
}
