<?php

namespace alcamo\rdfa;

use alcamo\html_creation\element\Publisher;

/**
 * @brief dc:publisher RDFa statement
 *
 * @sa [dc:publisher](http://purl.org/dc/terms/publisher).
 *
 * @date Last reviewed 2021-06-21
 */
class DcPublisher extends AbstractStmt
{
    use LiteralContentOrLinkTrait;

    public const PROPERTY_CURIE = 'dc:publisher';
}
