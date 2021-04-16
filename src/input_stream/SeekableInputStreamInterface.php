<?php

namespace alcamo\input_stream;

interface SeekableInputStreamInterface extends InputStreamInterface
{
    /// Get current pffset in input data
    public function getOffset(): int;

    /// Get size of complete input data
    public function getSize(): int;

    /// Get complete input data
    public function getContents(): string;

    /// Get remining input data without extracting it, if any
    public function getRemainder(): ?string;

    /// Extract remaining input data, if any
    public function extractRemainder(): ?string;
}
