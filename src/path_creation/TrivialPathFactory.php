<?php

namespace alcamo\path_creation;

// Class just prepending an optinal base to a relative path
class TrivialPathFactory implements PathFactoryInterface
{
    private $basePath_; ///< Path to prepend

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
