<?php

namespace alcamo\rdfa;

use alcamo\html_creation\element\Identifier;

/**
 * @sa [owl:versionInfo](https://www.w3.org/TR/owl-ref/#versionInfo-def).
 */
class OwlVersionInfo extends AbstractStmt
{
    use LiteralContentTrait;

    public const PROPERTY = 'owl:versionInfo';
}
