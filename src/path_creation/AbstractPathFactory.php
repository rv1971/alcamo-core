<?php

namespace alcamo\path_creation;

/**
 * @brief Factory creating absolute paths from relative ones
 *
 * @date Last reviewed 2021-06-15
 */
abstract class AbstractPathFactory implements PathFactoryInterface
{
    /// @copydoc PathFactoryInterface::createFromRelativePath()
    abstract public function createFromRelativePath(string $path): string;

    /**
     * @copydoc PathFactoryInterface::createFromRelativePaths()
     *
     * Apply createFromRelativePath() to each item and return an array of the
     * results, using the same keys as $paths.
     */
    public function createFromRelativePaths(iterable $paths): iterable
    {
        $result = [];

        foreach ($paths as $key => $path) {
            $result[$key] = $this->createFromRelativePath($path);
        }

        return $result;
    }
}
