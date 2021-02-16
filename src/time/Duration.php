<?php

/**
 * @file
 *
 * @brief Class Duration.
 */

namespace alcamo\time;

use alcamo\exception\SyntaxError;

class Duration extends \DateInterval
{
    /// Recognize fractions of a second
    public function __construct(string $string)
    {
        $a = explode('.', $string);

        if (!isset($a[1])) {
            // Literal without dot, understood by DateInterval constructor
            parent::__construct($string);
        } else {
            // After the dot there must be a number and an 'S'.
            if (!preg_match('/^([0-9]*)S$/', $a[1], $matches)) {
                throw new SyntaxError(
                    $string,
                    strlen($a[0]),
                    '; not a supported ISO 8601 duration'
                );
            }

            /* If there are only zeros before the dot, the part understood by
             * the DateInterval constructor must not contain seconds. */
            $str = rtrim($a[0], '0');

            if (!(int)$str[-1]) {
                parent::__construct(rtrim($str, 'T'));
            } else {
                parent::__construct("{$a[0]}S");
            }

            $this->f = (float)str_pad($a[1], 6, '0') / 1000000.;
        }
    }

    /// Return minimal ISO 8601 representation.
    public function __toString()
    {
        $format = '%rP';

        if ($this->days > 0) {
            $format .= '%aD';
        } else {
            if ($this->y) {
                $format .= '%yY';
            }

            if ($this->m) {
                $format .= '%mM';
            }

            if ($this->d) {
                $format .= '%dD';
            }
        }

        $timeFormat = '';

        if ($this->h) {
            $timeFormat .= '%hH';
        }

        if ($this->i) {
            $timeFormat .= '%iM';
        }

        if ($this->s || $this->f >= .5e-6) {
            $timeFormat .= '%s';

            if ($this->f >= .5e-6) {
                $timeFormat .= '.%F';
            }

            $timeFormat .= 'S';
        }

        if ($timeFormat != '') {
            $format .= "T$timeFormat";
        }

        return $this->format($format);
    }

    public function getTotalDays(): int
    {
      /** If months are specified, consider them to be of 30 days. */
        return $this->days > 0
        ? $this->days
        : $this->y * 365 + $this->m * 30 + $this->d;
    }


    public function getTotalHours(): int
    {
        return $this->getTotalDays() * 24 + $this->h;
    }

    public function getTotalMinutes(): int
    {
        return $this->getTotalHours() * 60 + $this->i;
    }

    public function getTotalSeconds(): float
    {
        return $this->getTotalMinutes() * 60 + $this->s + $this->f;
    }
}
