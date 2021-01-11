<?php

namespace alcamo\conf;

/**
 * @brief Parse a configuration file into an array.
 */

interface FileParserInterface {
  public function parse( string $filename ) : array;
}
