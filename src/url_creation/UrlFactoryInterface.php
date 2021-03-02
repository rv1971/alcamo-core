<?php

namespace alcamo\url_creation;

/// Create URL from path
interface UrlFactoryInterface
{
  /**
   * @param $path string Local path.
   *
   * @return URL.
   */
    public function createFromPath(string $path): string;
}
