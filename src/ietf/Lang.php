<?php

namespace alcamo\ietf;

use alcamo\exception\SyntaxError;

/**
 * @brief Language as in
 * [RFC4646](http://tools.ietf.org/html/rfc4646).
 *
 * @invariant Immutable class.
 *
 * @warning Only ISO 639 primary tags and ISO 3166-1 region subtags are
 * supported.
 *
 * @date Last reviewed 2021-06-17
 */
class Lang
{
    public const PRIMARY_TAG_REGEXP = '/^[a-z]{2,3}$/';
    public const REGION_TAG_REGEXP  = '/^[A-Z]{2}$/';

    private $primary_; ///< string
    private $region_;  ///< ?string

    public static function newFromString(string $string)
    {
        if (isset($string[2])) {
            return new self(substr($string, 0, 2), substr($string, 3));
        } else {
            return new self($string);
        }
    }

    /**
     * @param $primary @copybrief getPrimary()
     *
     * @param $region @copybrief getRegion()
     */
    public function __construct(string $primary, ?string $region = null)
    {
        if (!preg_match(static::PRIMARY_TAG_REGEXP, $primary)) {
            /** @throw alcamo::exception::SyntaxError if $primary is not a
             *  syntactically valid ISO 639 language. */
            throw (new SyntaxError())->setMessageContext(
                [
                    'inData' => $primary,
                    'extraMessage' => 'not a valid ISO 639 language'
                ]
            );
        }

        $this->primary_ = $primary;

        if (isset($region)) {
            if (!preg_match(static::REGION_TAG_REGEXP, $region)) {
                /** @throw alcamo::exception::SyntaxError if $region is not a
                 *  syntactically valid ISO 3166-1 alpha-2 code. */
                throw (new SyntaxError())->setMessageContext(
                    [
                        'inData' => $region,
                        'extraMessage' => 'not a valid ISO 3166-1 alpha-2 code'
                    ]
                );
            }

            $this->region_ = $region;
        }
    }

    /// Primary language subtag
    public function getPrimary(): string
    {
        return $this->primary_;
    }

    /// Region subtag
    public function getRegion(): ?string
    {
        return $this->region_;
    }

    /// Convert to RFC 4646 representation
    public function __toString(): string
    {
        return isset($this->region_)
            ? "{$this->primary_}-{$this->region_}"
            : $this->primary_;
    }
}
