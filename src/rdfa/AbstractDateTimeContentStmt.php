<?php

namespace alcamo\rdfa;

/**
 * @brief RDFa statement whose object is a DateTime object
 *
 * @date Last reviewed 2021-06-21
 */
abstract class AbstractDateTimeContentStmt extends AbstractStmt
{
    use LiteralContentTrait;

    public const OBJECT_CLASS   = \DateTime::class;

    /**
     * @brief Format used in __toString().
     *
     * @sa [DateTime::format()](https://www.php.net/manual/en/datetime.format)
     */
    public const DEFAULT_FORMAT = 'c';

    public function __construct(\DateTime $timestamp)
    {
        parent::__construct($timestamp, false);
    }

    /// Return content using @ref DEFAULT_FORMAT
    public function __toString()
    {
        return $this->format(static::DEFAULT_FORMAT);
    }

    /**
     * @param $format See
     * [DateTime::format()](https://www.php.net/manual/en/datetime.format).
     */
    public function format(string $format): string
    {
        return $this->getObject()->format($format);
    }
}
