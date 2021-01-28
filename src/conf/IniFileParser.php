<?php

namespace alcamo\conf;

use alcamo\exception\FileNotFound;

/**
 * @brief Parse an INI configuration file into an array.
 */

class IniFileParser implements FileParserInterface
{
    public function parse(string $filename): array
    {
        try {
            return parse_ini_file($filename, false, INI_SCANNER_TYPED);
        } catch (\Throwable $e) {
            throw new FileNotFound($filename);
        }
    }
}
