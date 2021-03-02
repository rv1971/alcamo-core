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

    /**
     * @param $paths iterable relative paths.
     *
     * @return iterable of paths.
     */
    public function createFromRelativePaths(iterable $paths): iterable;
}
