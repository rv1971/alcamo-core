<?php

namespace alcamo\http;

interface EmitInterface
{
    /**
     * @brief Write object content to output
     *
     * @return ?int Number of characters processed, or `null` on failure.
     */
    public function emit(): ?int;
}
