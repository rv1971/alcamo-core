<?php

namespace alcamo\path_creation;

/// Create path from relative path
interface PathFactoryInterface
{
  /**
   * @param $path string relative path.
   *
   * @return path.
   */
    public function createFromRelativePath(string $path): string;
}
