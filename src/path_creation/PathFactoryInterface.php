<?php

namespace alcamo\path_creation;

/**
 * @brief Factory creating absolut paths from relative ones
 *
 * @date Last reviewed 2021-06-15
 */
interface PathFactoryInterface
{
    /**
     * @param $path string relative path.
     *
     * @return path.
     */
    public function createFromRelativePath(string $path): string;

    /**
     * @param $paths iterable relative paths.
     *
     * @return iterable of paths.
     */
    public function createFromRelativePaths(iterable $paths): iterable;
}
