<?php

namespace alcamo\input_stream;

use alcamo\exception\{Eof, Underflow};

class StringInputStream implements SeekableInputStreamInterface
{
    public const WS_REGEXP = '/^\s+/';

    protected $text_;
    protected $offset_;

    public function __construct(string $text, ?int $offset = null)
    {
        $this->text_ = $text;
        $this->offset_ = (int)$offset;
    }

    public function __toString()
    {
        return $this->text_;
    }

    public function isGood(): bool
    {
        return isset($this->text_[$this->offset_]);
    }

    public function peek(): ?string
    {
        return $this->text_[$this->offset_] ?? null;
    }

    public function extract(int $count = 1): ?string
    {
        if (!isset($this->text_[$this->offset_])) {
            return null;
        }

        if (!isset($this->text_[$this->offset_ + $count - 1])) {
            throw new Eof($this, $count, strlen($this->text_) - $this->offset_);
        }

        $result = substr($this->text_, $this->offset_, $count);

        $this->offset_ += $count;

        return $result;
    }

    public function putback()
    {
        if ($this->offset_) {
            $this->offset_--;
        } else {
            throw new Underflow($this);
        }
    }

    public function extractUntil(
        string $sep,
        ?int $maxCount = null,
        ?bool $extractSep = null,
        ?bool $discardSep = null
    ): ?string {
        if (!isset($this->text_[$this->offset_])) {
            return null;
        }

        $sepPos = strpos($this->text_, $sep, $this->offset_);

        if ($sepPos === false) {
            // If not found, return $maxCount or the entire remainder.
            if (
                isset($maxCount)
                && $this->offset_ + $maxCount <= strlen($this->text_)
            ) {
                $result = substr($this->text_, $this->offset_, $maxCount);
                $this->offset_ += $maxCount;
            } else {
                $result = substr($this->text_, $this->offset_);
                $this->offset_ = strlen($this->text_);
            }
        } else {
            // If found, return $maxCount or until $sep.
            if ($extractSep) {
                $sepPos += strlen($sep);
            }

            if (isset($maxCount) && $sepPos > $this->offset_ + $maxCount) {
                $sepPos = $this->offset_ + $maxCount;

                $result = substr($this->text_, $this->offset_, $maxCount);
            } else {
                $result = substr(
                    $this->text_,
                    $this->offset_,
                    $discardSep
                    ? $sepPos - $this->offset_ - strlen($sep)
                    : $sepPos - $this->offset_
                );
            }

            $this->offset_ = $sepPos;
        }

        return $result;
    }

    public function getOffset(): int
    {
        return $this->offset_;
    }

    public function getSize(): int
    {
        return strlen($this->text_);
    }

    public function getContents(): string
    {
        return $this->text_;
    }

    /**
     * @brief Extract regular expression.
     *
     * @return ?string, match $matchNo in the array of matches returned by
     * preg_match().
     */
    public function extractRegexp(string $regexp, ?int $matchNo = 0): ?string
    {
        if (
            preg_match(
                $regexp,
                mb_substr($this->text_, $this->offset_),
                $matches,
                PREG_OFFSET_CAPTURE
            )
        ) {
            $this->offset_ += mb_strlen($matches[0][0]) + $matches[0][1];

            return $matches[(int)$matchNo][0];
        }
    }

    public function extractWs(): ?string
    {
        return $this->extractRegexp(static::WS_REGEXP);
    }
}
