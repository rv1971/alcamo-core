<?php

namespace alcamo\rdfa;

/**
 * @sa [dc:created](http://purl.org/dc/terms/created).
 */
class DcCreated extends AbstractStmt
{
    public const PROPERTY = 'dc:created';
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
}
