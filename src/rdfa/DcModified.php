<?php

namespace alcamo\rdfa;

/**
 * @sa [dc:modified](http://purl.org/dc/terms/modified).
 */
class DcModified extends AbstractStmt
{
    public const PROPERTY     = 'dc:modified';
    public const HTTP_HEADER  = 'Last-Modified';
    public const OBJECT_CLASS = \DateTime::class;

    public function __construct(\DateTime $timestamp)
    {
        parent::__construct($timestamp, false);
    }

    public function __toString()
    {
        return $this->format('c');
    }

    public function format(string $format): string
    {
        return $this->getObject()->format($format);
    }

    public function toHttpHeaders(): array
    {
        return [ static::HTTP_HEADER => $this->format('r') ];
    }
}
