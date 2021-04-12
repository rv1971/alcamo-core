<?php

namespace alcamo\simple_input_stream;

interface SeekableInputStreamInterface extends InputStreamInterface
{
    public function getOffset(): int;

    public function getSize(): int;

    public function getContents(): string;
}
