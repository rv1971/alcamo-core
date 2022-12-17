<?php

namespace alcamo\ietf;

use alcamo\exception\SyntaxError;

/**
 * @brief Language as in
 * [RFC4646](http://tools.ietf.org/html/rfc4646).
 *
 * @invariant Immutable class.
 *
 * @warning Only languages with an ISO 639 primary tag, zero or one ISO 3166-1
 * region subtag and zero or more private subtags are supported.
 */
class Lang
{
    public const PRIMARY_SUBTAG_REGEXP = '/^[a-z]{2,3}$/';
    public const REGION_SUBTAG_REGEXP  = '/^[A-Z]{2}$/';
    public const PRIVATE_SUBTAGS_REGEXP = '/^[0-9A-Za-z]{1,8}(-[0-9A-Za-z]{1,8})*$/';

    private $primary_; ///< string
    private $region_;  ///< ?string
    private $private_; ///< ?string

    public static function newFromString(string $string)
    {
        $subtags = explode('-', $string);

        $primary = array_shift($subtags);

        if (!$subtags) {
            return new self($primary);
        } else {
            $nextSubtag = array_shift($subtags);

            if ($nextSubtag == 'X' || $nextSubtag == 'x') {
                $region = null;
            } else {
                $region = $nextSubtag;

                if ($subtags) {
                    $nextSubtag = array_shift($subtags);
                } else {
                    return new self($primary, $region);
                }
            }
        }

        if (($nextSubtag != 'X' && $nextSubtag != 'x') || !$subtags) {
            /** @throw alcamo::exception::SyntaxError if remaining tags
             *  do not match the privateuse production. */
            array_unshift($subtags, $nextSubtag);

            throw (new SyntaxError())->setMessageContext(
                [
                    'inData' => implode('-', $subtags),
                    'extraMessage' => 'not a valid privateuse tag'
                ]
            );
        }

        return new self($primary, $region, implode('-', $subtags));
    }

    public static function newFromLocale(?string $locale = null): self
    {
        if (!isset($locale)) {
            $locale = \Locale::getDefault();
        }

        $a = \Locale::parseLocale($locale);

        return isset($a['region'])
            ? new self($a['language'], $a['region'])
            : new self($a['language']);
    }

    /**
     * @param $primary @copybrief getPrimary()
     *
     * @param $region @copybrief getRegion()
     */
    public function __construct(
        string $primary,
        ?string $region = null,
        ?string $private = null
    ) {
        if (!preg_match(static::PRIMARY_SUBTAG_REGEXP, $primary)) {
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
            if (!preg_match(static::REGION_SUBTAG_REGEXP, $region)) {
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

        if (isset($private)) {
            if (!preg_match(static::PRIVATE_SUBTAGS_REGEXP, $private)) {
                /** @throw alcamo::exception::SyntaxError if $private is not a
                 *  syntactically valid sequence of private subtags. */
                throw (new SyntaxError())->setMessageContext(
                    [
                        'inData' => $private,
                        'extraMessage' => 'not a valid privateuse tag'
                    ]
                );
            }

            $this->private_ = $private;
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

    /// Private subtags
    public function getPrivate(): ?string
    {
        return $this->private_;
    }

    /// Convert to RFC 4646 representation
    public function __toString(): string
    {
        $result = $this->primary_;

        if (isset($this->region_)) {
            $result .= "-{$this->region_}";
        }

        if (isset($this->private_)) {
            $result .= "-x-{$this->private_}";
        }

        return $result;
    }
}
