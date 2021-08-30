<?php

namespace alcamo\exception;

/**
 * @brief Location in a file
 *
 * @date Last reviewed 2021-06-07
 */
class FileLocation
{
    private $filename_; ///< convertable to string
    private $line_;     ///< ?int
    private $column_;   ///< ?int

    public static function newFromThrowable(\Throwable $throwable): self
    {
        return new static($throwable->getFile(), $throwable->getLine());
    }

    public static function newFromBacktraceItem(array $frame): self
    {
        return new static($frame['file'] ?? null, $frame['line'] ?? null);
    }

    public function __construct(
        $filename,
        ?int $line = null,
        ?int $column = null
    ) {
        $this->filename_ = $filename;
        $this->line_ = $line;
        $this->column_ = $column;
    }

    public function getFilename(): ?string
    {
        return $this->filename_;
    }

    public function getLine(): ?int
    {
        return $this->line_;
    }

    public function getColumn(): ?int
    {
        return $this->column_;
    }

    public function __toString(): string
    {
        $result = [];

        if ($this->filename_) {
            $result[] = $this->filename_;
        }

        if ($this->line_) {
            $result[] = $this->line_;
        }

        if ($this->column_) {
            if ($result) {
                $result[] = $this->column_;
            } else {
                $result[] = "column $this->column_";
            }
        }

        return implode(':', $result);
    }
}
