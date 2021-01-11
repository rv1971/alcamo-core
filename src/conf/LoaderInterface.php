<?php

namespace alcamo\conf;

/**
 * @brief Load and merge configuration files.
 */

interface LoaderInterface {
  public function load( array $filenames ) : array;
}
