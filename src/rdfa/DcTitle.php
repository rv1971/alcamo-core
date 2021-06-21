<?php

namespace alcamo\rdfa;

use alcamo\html_creation\element\Title;
use alcamo\xml_creation\Nodes;

/**
 * @brief dc:title RDFa statement
 *
 * @sa [dc:title](http://purl.org/dc/terms/title).
 *
 * @date Last reviewed 2021-06-21
 */
class DcTitle extends AbstractStmt
{
    use LiteralContentTrait;

    public const PROPERTY_CURIE = 'dc:title';

    public function toHtmlNodes(): ?Nodes
    {
        return new Nodes(new Title(
            $this->getObject(),
            [ 'property' => static::PROPERTY_CURIE ]
        ));
    }
}
