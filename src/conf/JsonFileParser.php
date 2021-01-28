<?php

namespace alcamo\conf;

/**
 * @brief Parse a JSON configuration file into an array.
 */

use alcamo\exception\FileNotFound;

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
