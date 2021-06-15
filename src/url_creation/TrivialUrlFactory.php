<?php

namespace alcamo\url_creation;

/**
 * @brief Factory just prepending an optional base to a relative path
 *
 * @date Last reviewed 2021-06-15
 */
class TrivialUrlFactory implements UrlFactoryInterface
{
    private $baseUrl_; ///< ?string

    public function __construct(?string $baseUrl = null)
    {
        $this->baseUrl_ = $baseUrl;
    }

    public function getBaseUrl(): ?string
    {
        return $this->baseUrl_;
    }

    /**
     * @warning It is not checked whether the supplied path is actually
     * relative.
     */
    public function createFromPath(string $path): string
    {
        return $this->baseUrl_
            . (DIRECTORY_SEPARATOR == '/'
               ? $path
               : str_replace(DIRECTORY_SEPARATOR, '/', $path));
    }
}
