<?php

namespace alcamo\rdfa;

trait LiteralContentOrLinkTrait
{
    // Treat first argument as literal unless second argument is given
    public function __construct($content, $resourceLabel = null)
    {
        parent::__construct($content, $resourceLabel ?? false);
    }
}
