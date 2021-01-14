<?php

namespace alcamo\conf;

/**
 * @brief Load JSON or INI files from $XDG_DATA_DIRS/subdir.
 */

use alcamo\exception\FileNotFound;

class Loader implements LoaderInterface {
  private $fileFinder_;
  private $fileParser_;

  public function __construct(
    FileFinderInterface $fileFinder = null,
    FileParserInterface $fileParser = null
  ) {
    $this->fileFinder_ = $fileFinder ?? new XdgFileFinder();
    $this->fileParser_ = $fileParser ?? new FileParser();
  }

  public function getFileFinder() : FileFinderInterface {
    return $this->fileFinder_;
  }

  public function getFileParser() : FileParserInterface {
    return $this->fileParser_;
  }

  /**
   * @brief Load and parse files.
   *
   * @param $filename array|string file names to find and to load
   *
   * Each file is parsed into an array. The arrays are merged such that files
   * earlier in the list take precedence over files later in the list.
   *
   * @return Array of the contents of all files.
   */
  public function load( $filenames ) : array {
    $result = [];

    foreach ( (array)$filenames as $filename ) {
      $pathname = $this->fileFinder_->find( $filename );

      if ( !isset( $pathname ) ) {
        throw new FileNotFound( $filename, (string)$this->fileFinder_ );
      }

      $result += $this->fileParser_->parse( $pathname );
    }

    return $result;
  }
}