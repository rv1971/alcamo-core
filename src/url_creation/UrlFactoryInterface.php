<?php

namespace alcamo\url_creation;

/// Create Url from Path
interface UrlFactoryInterface
{
  /**
   * @param $path string Local path.
   *
   * @return Url.
   */
    public function createFromPath(string $path): string;
}
