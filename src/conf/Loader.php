<?php

namespace alcamo\conf;

use alcamo\exception\FileNotFound;

/**
 * @brief Load JSON or INI files from $XDG_DATA_DIRS/subdir.
 */

class Loader implements LoaderInterface
{
    private $fileFinder_;
    private $fileParser_;

    public function __construct(
        ?FileFinderInterface $fileFinder = null,
        ?FileParserInterface $fileParser = null
    ) {
        $this->fileFinder_ = $fileFinder ?? new XdgFileFinder();
        $this->fileParser_ = $fileParser ?? new FileParser();
    }

    public function getFileFinder(): FileFinderInterface
    {
        return $this->fileFinder_;
    }

    public function getFileParser(): FileParserInterface
    {
        return $this->fileParser_;
    }

    /**
     * @brief Load and parse files.
     *
     * @param $filename iterable|string file names to find and to load
     *
     * Each file is parsed into an array. The arrays are merged such that
     * files later in the list take precedence over files earlier in the list.
     *
     * @return Array of the contents of all files.
     */
    public function load($filenames): array
    {
        $result = [];

        if (!is_iterable($filenames)) {
            $filenames = (array)$filenames;
        }

        foreach ($filenames as $filename) {
            $pathname = $this->fileFinder_->find($filename);

            if (!isset($pathname)) {
                throw new FileNotFound($filename, (string)$this->fileFinder_);
            }

            $result = $this->fileParser_->parse($pathname) + $result;
        }

        return $result;
    }
}
