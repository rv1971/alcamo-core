<?php

namespace alcamo\conf;

/**
 * @brief Parse an INI or JSON configuration file into an array.
 */

use alcamo\exception\InvalidEnumerator;

class FileParser implements FileParserInterface {
  private $ext2parser_; // Map of filename extensions to parser objects

  public function __construct() {
    $this->ext2parser_ = [
      'ini' => new IniFileParser(),
      'json' => new JsonFileParser()
    ];
  }

  public function parse( string $filename ) : array {
    $extension = pathinfo( $filename, PATHINFO_EXTENSION );

    if ( isset( $this->ext2parser_[$extension] ) ) {
      return $this->ext2parser_[$extension]->parse( $filename );
    }

    $extensions = array_keys( $this->ext2parser_ );

    throw new InvalidEnumerator(
      $extension,
      $extensions,
      "Invalid file extension in '$filename', expected one of: '"
      . implode( "', '", $extensions ) . "'"
    );
  }
}
