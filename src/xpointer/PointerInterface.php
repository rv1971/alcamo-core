<?php

namespace alcamo\xpointer;

interface PointerInterface
{
    public static function newFromString(string $fragment);

    public function process(\DOMDocument $doc);
}
