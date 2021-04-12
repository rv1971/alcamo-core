<?php

namespace alcamo\simple_input_stream;

class StringInputStream implements SeekableInputStreamInterface
{
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
        if (isset($this->text_[$this->offset_ + $count - 1])) {
            $result = substr($this->text_, $this->offset_, $count);

            $this->offset_ += $count;

            return $result;
        } else {
            throw new Eof($this, $count);
        }
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
        ?bool $extractSep = null
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
                $this->offset_ += $maxCount_;
            } else {
                $result = substr($this->data_, $this->offset_);
                $this->offset_ = strlen($this->text_);
            }
        } else {
            // If found, return $maxCount or until $sep.
            if ($extractSep) {
                $sepPos += strlen($sep);
            }

            if (isset($maxCount) && $sepPos > $this->offset_ + $maxCount) {
                $sepPos = $this->offset_ + $maxCount;
            }

            $result = substr(
                $this->data_,
                $this->offset_,
                $sepPos - $this->offset_
            );

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
}
