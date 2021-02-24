<?php

namespace alcamo\rdfa;

use alcamo\html_creation\element\Rights;

/**
 * @sa [dc:rights](http://purl.org/dc/terms/rights).
 */
class DcRights extends AbstractStmt
{
    use LiteralContentOrLinkTrait;

    public const PROPERTY = 'dc:rights';
}
