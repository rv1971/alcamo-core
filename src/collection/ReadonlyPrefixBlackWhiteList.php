<?php

namespace alcamo\collection;

use Ds\Set;

/**
 * @brief Blacklist or whitelist of prefixes
 *
 * @warning The prefixes must not contain pipe characters because of a
 * restriction of the parent class.
 */
class ReadonlyPrefixBlackWhiteList extends ReadonlyPrefixSet
{
    use BlackWhiteListTrait;

    /// Create from whitespace-separated list of prefixes
    public static function newFromStringAndBool(
        string $prefixText,
        ?bool $isBlacklist = null
    ): ReadonlyPrefixSet {
        return new self(
            new Set(preg_split('/\s+/', $prefixText)),
            $isBlacklist
        );
    }

    /**
     * @brief Create from whitespace-separated list of prefixes
     *
     * Return a blacklist if the first character is an exclamation mark.
     */
    public static function newFromStringWithOperator(
        string $prefixText
    ): ReadonlyPrefixSet {
        if ($prefixText[0] == '!') {
            $isBlacklist = true;
            $prefixText = ltrim(substr($prefixText, 1));
        } else {
            $isBlacklist = false;
        }

        return self::newFromStringAndBool($prefixText, $isBlacklist);
    }

    /**
     * @param $prefixes Set of prefixes.
     *
     * @param $isBlacklist Whether the prefixes form a blacklist rather than a
     * whitelist.
     */
    public function __construct(Set $prefixes, ?bool $isBlacklist = null)
    {
        parent::__construct($prefixes);

        $this->isBlacklist_ = (bool)$isBlacklist;
    }
}
