<?php

namespace alcamo\rdfa;

use alcamo\time\Duration;

/**
 * @sa [Expires](http://tools.ietf.org/html/rfc2616#section-14.21)
 */
class HeaderExpires extends AbstractStmt
{
    use NoHtmlTrait;

    public const PROPERTY = 'header:expires';
    public const OBJECT_CLASS = Duration::class;

    public function __construct(Duration $duration)
    {
        parent::__construct($duration, false);
    }

  /// Set session parameters accordingly.
    public function alterSession()
    {
        session_cache_expire($this->getObject()->getTotalMinutes());
    }
}