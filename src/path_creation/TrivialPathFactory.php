<?php

namespace alcamo\path_creation;

/**
 * @brief Factory just prepending an optional base to a relative path
 *
 * @date Last reviewed 2021-06-15
 */
class TrivialPathFactory extends AbstractPathFactory
{
    private $basePath_; ///< ?string

    public function __construct(?string $basePath = null)
    {
        if ($basePath && $basePath[-1] != DIRECTORY_SEPARATOR) {
            $basePath .= DIRECTORY_SEPARATOR;
        }

        $this->basePath_ = $basePath;
    }

    public function getBasePath(): ?string
    {
        return $this->basePath_;
    }

    /**
     * @warning It is not checked whether the supplied path is actually
     * relative.
     */
    public function createFromRelativePath(string $path): string
    {
        return "{$this->basePath_}$path";
    }
}
