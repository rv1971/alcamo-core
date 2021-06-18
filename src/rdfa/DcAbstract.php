<?php

namespace alcamo\rdfa;

/**
 * @brief dc:abstract RDFa statement
 *
 * @sa [dc:abstract](http://purl.org/dc/terms/abstract).
 *
 * @date Last reviewed 2021-06-18
 */
class DcAbstract extends AbstractStmt
{
    use LiteralContentTrait;

    public const PROPERTY_CURIE = 'dc:abstract';
    public const META_NAME      = 'description';
}
