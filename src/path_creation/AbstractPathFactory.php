<?php

namespace alcamo\path_creation;

abstract class AbstractPathFactory implements PathFactoryInterface
{
    abstract public function createFromRelativePath(string $path): string;

    public function createFromRelativePaths(iterable $paths): iterable
    {
        $result = [];

        foreach ($paths as $path) {
            $result[] = $this->createFromRelativePath($path);
        }

        return $result;
    }
}
