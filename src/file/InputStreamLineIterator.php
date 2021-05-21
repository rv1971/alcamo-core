<?php

namespace alcamo\file;

use alcamo\exception\Unsupported;
use alcamo\iterator\IteratorCurrentTrait;

class InputStreamLineIterator implements \Iterator
{
    use IteratorCurrentTrait;

    /// Whether to include the line delimiter into the result of next()
    public const INCLUDE_LINE_DELIMITER = 1;

    /// Whether to skip empty lines
    public const SKIP_EMPTY = 2;

    private $handle_; ///< resource
    private $flags_;  ///< int

    public function __construct($handle, int $flags = null)
    {
        $this->handle_ = $handle;
        $this->flags_ = (int)$flags;

        $this->currentKey_ = 1;
        $this->current_ = $this->readLine();
    }

    public function getFlags(): int
    {
        return $this->flags_;
    }

    public function rewind()
    {
        if ($this->currentKey_ > 1) {
            throw new Unsupported('rewind');
        }
    }

    public function next()
    {
        if (isset($this->current_)) {
            $this->currentKey_++;
            $this->current_ = $this->readLine();
        }
    }

    protected function readLine()
    {
        $line = fgets($this->handle_);

        if ($line === false) {
            return null;
        } else {
            if (
                $this->flags_ & self::SKIP_EMPTY && rtrim($line, PHP_EOL) == ''
            ) {
                return $this->readLine();
            }

            if (!($this->flags_ & self::INCLUDE_LINE_DELIMITER)) {
                $line = rtrim($line, PHP_EOL);
            }

            return $line;
        }
    }
}
