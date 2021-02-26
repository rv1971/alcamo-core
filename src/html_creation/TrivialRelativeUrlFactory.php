<?php

namespace alcamo\html_creation;

// Class returning a relative path unchanged
class TrivialRelativeUrlFactory extends AbstractUrlFactory
{
    private $baseUrl_; ///< URL to prepend

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
