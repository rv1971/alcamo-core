<?php

namespace alcamo\conf;

use XdgBaseDir\Xdg;
use alcamo\exception\InvalidEnumerator;

/**
 * @brief Find a file as explained by the XDG Base Directory Specification.
 *
 * @sa [XDG Base Directory Specification](https://specifications.freedesktop.org/basedir-spec/basedir-spec-latest.html)
 */

class XdgFileFinder extends \XdgBaseDir\Xdg implements FileFinderInterface
{
    public const SUBDIR = 'alcamo';

    private $subdir_;
    private $dirs_;

    /**
     * @param string $type `CONFIG` or `DATA`.
     *
     * @throw InvalidEnumerator if `$type` is invalid.
     */
    public function __construct(?string $subdir = null, ?string $type = null)
    {
        $this->subdir_ = $subdir ?? static::SUBDIR;

        switch ($type ?? 'CONFIG') {
            case 'CONFIG':
                $this->dirs_ = $this->getConfigDirs();
                break;

            case 'DATA':
                $this->dirs_ = $this->getDataDirs();
                break;

            default:
                throw new InvalidEnumerator($type, [ 'CONFIG', 'DATA' ]);
        }
    }

    public function getSubdir(): string
    {
        return $this->subdir_;
    }

    public function __toString()
    {
        return implode(':', $this->dirs_);
    }

    public function find(string $filename): ?string
    {
        foreach ($this->dirs_ as $dir) {
            $pathname = $dir . DIRECTORY_SEPARATOR
                . $this->subdir_ . DIRECTORY_SEPARATOR
                . $filename;

            if (is_readable($pathname)) {
                return $pathname;
            }
        }

        return null;
    }
}
