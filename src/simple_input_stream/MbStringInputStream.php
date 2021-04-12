<?php

namespace alcamo\simple_input_stream;

class MbStringInputStream extends StringInputStream
{
    public const WS_REGEXP = '/^\s+/';

    protected $length_;

    public function __construct(string $text, ?int $offset = null)
    {
        parent::__construct($text, $offset);

        /** Cache length in mb_strlen() which has complexity O(n).  @sa [Which
         * complexity
         * mb_strlen?](http://stackoverflow.com/questions/40597394/which-complexity-mb-strlen)
     */
        $this->length_ = mb_strlen($this->text_);
    }

    public function isGood(): bool
    {
        return $this->offset_ < $this->length_;
    }

    public function peek(): ?string
    {
        return $this->offset_ < $this->length_
            ? mb_substr($this->data_, $this->offset_, 1)
            : null;
    }

    public function extract(int $count = 1): ?string
    {
        if ($this->offset_ >= $this->length_) {
            return null;
        }

        $result = mb_substr($this->text_, $this->offset_, $count);

        if (mb_strlen($result) != $count) {
            throw new Eof($this, $count);
        }

        $this->offset_ += $count;

        return $result;
    }

    public function extractUntil(
        string $sep,
        ?int $maxCount = null,
        ?bool $extractSep = null
    ): ?string {
        if ($this->offset_ >= $this->length_) {
            return null;
        }

        $sepPos = mb_strpos($this->text_, $sep, $this->offset_);

        if ($sepPos === false) {
            // If not found, return $maxCount or the entire remainder.
            if (
                isset($maxCount)
                && $this->offset_ + $maxCount <= mb_strlen($this->text_)
            ) {
                $result = mb_substr($this->text_, $this->offset_, $maxCount);
                $this->offset_ += $maxCount_;
            } else {
                $result = mb_substr($this->data_, $this->offset_);
                $this->offset_ = $this->length_;
            }
        } else {
            // If found, return $maxCount or until $sep.
            if ($extractSep) {
                $sepPos += mb_strlen($sep);
            }

            if (isset($maxCount) && $sepPos > $this->offset_ + $maxCount) {
                $sepPos = $this->offset_ + $maxCount;
            }

            $result = mb_substr(
                $this->data_,
                $this->offset_,
                $sepPos - $this->offset_
            );

            $this->offset_ = $sepPos;
        }

        return $result;
    }

    public function getSize(): int
    {
        return mb_strlen($this->text_);
    }

    /// Skip regular expression.
    public function skipRegexp(string $regexp)
    {
        if (
            preg_match(
            $regexp,
            mb_substr($this->text_, $this->offset_),
            $matches
            )
        ) {
            $this->offset_ += mb_strlen($matches[0]);
        }
    }

    public function skipWs()
    {
        $this->skipRegexp(static::WS_REGEXP);
    }
}
