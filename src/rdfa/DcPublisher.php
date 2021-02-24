<?php

namespace alcamo\rdfa;

use alcamo\html_creation\element\Publisher;

/**
 * @sa [dc:publisher](http://purl.org/dc/terms/publisher).
 */
class DcPublisher extends AbstractStmt
{
    use LiteralContentOrLinkTrait;

    public const PROPERTY = 'dc:publisher';
}
