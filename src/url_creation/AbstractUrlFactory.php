<?php

namespace alcamo\url_creation;

use alcamo\exception\FileNotFound;

/**
 * @brief Factory creating URLs from local paths
 *
 * @date Last reviewed 2021-06-15
 */
abstract class AbstractUrlFactory implements UrlFactoryInterface
{
    private $disablePreferGz_; ///< bool

    private $disableAppendMtime_; ///< bool

    /**
     * @param $disablePreferGz @copybrief getDisablePreferGz()
     *
     * @param $disableAppendMtime @copybrief getDisableAppendMtime()
     */
    public function __construct(
        ?bool $disablePreferGz = null,
        ?bool $disableAppendMtime = null
    ) {
        $this->disablePreferGz_ = (bool)$disablePreferGz;
        $this->disableAppendMtime_ = (bool)$disableAppendMtime;
    }

    /// Whether to avoid preferring a gzipped version of the resource
    public function getDisablePreferGz(): bool
    {
        return $this->disablePreferGz_;
    }

    /// Whether to avoid appending the file timestamp as a GET parameter
    public function getDisableAppendMtime(): bool
    {
        return $this->disableAppendMtime_;
    }

    /// Create local path using the gzipped version, if desired
    public function createActualLocalPath(string $path): string
    {
        if (!is_readable($path)) {
            /** @throw alcamo::exception::FileNotFound if $path cannot be
             *  read. */
            throw (new FileNotFound())
                ->setMessageContext([ 'filename' => $path ]);
        }

        if (!$this->disablePreferGz_) {
            /* The gzipped file has the additional suffix .gz except for SVG
             * files where the suffix .svg becomes .svgz. */
            $gzPath = substr($path, -4) == '.svg'
                ? "${path}z"
                : "$path.gz";

            if (is_readable($gzPath)) {
                return $gzPath;
            }
        }

        return $path;
    }

    /// Create query to append to the URL
    public function createQuery(string $path): ?string
    {
        return $this->disableAppendMtime_
            ? null
            : '?m=' . gmdate('YmdHis', filemtime($path));
    }

    /// @copydoc UrlFactoryInterface::createFromPath()
    abstract public function createFromPath(string $path): string;
}
