<?php

namespace alcamo\url_creation;

use alcamo\exception\FileNotFound;

abstract class AbstractUrlFactory implements UrlFactoryInterface
{
    /// Whether to prefer a gzipped version, if available
    private $disablePreferGz_;

    /// Whether to append the file modification timestamp as a GET parameter
    private $disableAppendMtime_;

    public function __construct(
        ?bool $disablePreferGz = null,
        ?bool $disableAppendMtime = null
    ) {
        $this->disablePreferGz_ = (bool)$disablePreferGz;
        $this->disableAppendMtime_ = (bool)$disableAppendMtime;
    }

    public function getDisablePreferGz(): bool
    {
        return $this->disablePreferGz_;
    }

    public function getDisableAppendMtime(): bool
    {
        return $this->disableAppendMtime_;
    }

    public function realpath(string $path): string
    {
        $realpath = realpath($path);

        if (!$realpath) {
          /** @throw FileNotFound if realpath of $path culd not be obtained. */
            throw new FileNotFound($path);
        }

      /* The gzipped file has the additional suffix .gz except for SVG files
       * where the suffix .svg becomes .svgz. */
        $gzPath =
        substr($realpath, -4) == '.svg' ? "${realpath}z" : "$realpath.gz";

        if (!$this->disablePreferGz_ && is_readable($gzPath)) {
            return $gzPath;
        }

        return $realpath;
    }

    public function createQuery(string $path): ?string
    {
        return $this->disableAppendMtime_
            ? null
            : '?m=' . gmdate('YmdHis', filemtime($path));
    }

    abstract public function createFromPath(string $path): string;
}
