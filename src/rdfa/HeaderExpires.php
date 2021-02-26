<?php

namespace alcamo\rdfa;

use alcamo\time\Duration;

/**
 * @sa [Expires](http://tools.ietf.org/html/rfc2616#section-14.21)
 */
class HeaderExpires extends AbstractStmt
{
    use NoHtmlTrait;
    use NoPrefixBindingTrait;

    public const PROPERTY_CURIE = 'header:expires';
    public const HTTP_HEADER    = 'Expires';
    public const OBJECT_CLASS   = Duration::class;

    public function __construct(Duration $duration)
    {
        parent::__construct($duration, false);
    }

    public function toHttpHeaders(): array
    {
        return [
            static::HTTP_HEADER => [
                (new \DateTimeImmutable())->add($this->getObject())->format('r')
            ]
        ];
    }

    /// Set session parameters accordingly.
    public function alterSession()
    {
        session_cache_expire($this->getObject()->getTotalMinutes());
    }
}
