<?php

namespace alcamo\rdfa;

/**
 * @brief owl:versionInfo RDFa statement
 *
 * @sa [owl:versionInfo](https://www.w3.org/TR/owl-ref/#versionInfo-def).
 *
 * @date Last reviewed 2021-06-21
 */
class OwlVersionInfo extends AbstractStmt
{
    use LiteralContentTrait;

    public const PROPERTY_CURIE = 'owl:versionInfo';
}
