<?php

namespace alcamo\rdfa;

use alcamo\html_creation\element\ConformsTo;

/**
 * @sa [dc:conformsTo](http://purl.org/dc/terms/conformsTo).
 */
class DcConformsTo extends AbstractStmt
{
    public const PROPERTY = 'dc:conformsTo';

    public function __construct($conformsTo)
    {
        parent::__construct($conformsTo, true);
    }
}
