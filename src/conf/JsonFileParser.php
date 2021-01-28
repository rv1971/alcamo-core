<?php

namespace alcamo\conf;

use alcamo\exception\FileNotFound;

/**
 * @brief Parse a JSON configuration file into an array.
 */

class JsonFileParser implements FileParserInterface
{
    public function parse(string $filename): array
    {
        try {
            $contents = file_get_contents($filename);
        } catch (\Throwable $e) {
            throw new FileNotFound($filename);
        }

        return json_decode($contents, true);
    }
}
