<?php

namespace alcamo\rdfa;

/**
 * @brief RDFa statement whose object is always literal content
 *
 * @date Last reviewed 2021-06-18
 */
trait LiteralContentTrait
{
    public function __construct($content)
    {
        parent::__construct($content, false);
    }
}
