<?php

namespace alcamo\rdfa;

/**
 * @brief RDFa statement whose object is either literal content or a link
 *
 * @date Last reviewed 2021-06-21
 */
trait LiteralContentOrLinkTrait
{
    /// Treat first argument as literal unless second argument is given
    public function __construct($content, $resourceInfo = null)
    {
        parent::__construct($content, $resourceInfo ?? false);
    }
}
