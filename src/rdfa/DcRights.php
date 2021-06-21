<?php

namespace alcamo\rdfa;

use alcamo\html_creation\element\Rights;

/**
 * @brief dc:rights RDFa statement
 *
 * @sa [dc:rights](http://purl.org/dc/terms/rights).
 *
 * @date Last reviewed 2021-06-21
 */
class DcRights extends AbstractStmt
{
    use LiteralContentOrLinkTrait;

    public const PROPERTY_CURIE = 'dc:rights';
}
