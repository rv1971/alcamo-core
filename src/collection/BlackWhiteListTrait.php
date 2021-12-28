<?php

namespace alcamo\collection;

/// Common code for black/white-lists
trait BlackWhiteListTrait
{
    private $isBlacklist_; ///< bool

    /// Whether this is a blacklist
    public function isBlacklist(): bool
    {
        return $this->isBlacklist_;
    }

    /// Whether $value is allowed by this list
    public function allows(string $value): bool
    {
        /**
         * Returns `true` if either
         * - contains() returns `true` and this a whitelist, or
         * - contains() returns `false` and this a blacklist
         */
        return $this->contains($value) xor $this->isBlacklist_;
    }
}
