<?php

namespace alcamo\conf;

interface FileFinderInterface
{
    public function find(string $filename): ?string;

    public function __toString();
}
