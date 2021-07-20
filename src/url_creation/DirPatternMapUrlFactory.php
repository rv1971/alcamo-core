<?php

namespace alcamo\url_creation;

/**
 * @brief Factory mapping a htdocs directory pattern to a htdocs URL
 */
class DirPatternMapUrlFactory extends AbstractUrlFactory
{
    private $htdocsDirPattern_; ///< string
    private $dirSegmentCount_; ///< int

    private $htdocsUrl_; ///< string

    /**
     * @brief Create from associative array ot ArrayAccess object
     *
     * @param $conf array|ArrayAccess must contain the keys `htdocsDirPattern`
     * and `htdocsUrl`, may contain the keys `disablePreferGz` and
     * `disableAppendMtime`
     */
    public function newFromConf($conf): self
    {
        return new self(
            $conf['htdocsDirPattern'],
            $conf['htdocsUrl'],
            $conf['disablePreferGz'] ?? null,
            $conf['disableAppendMtime'] ?? null
        );
    }

    /**
     * @param $htdocsDirPattern @copybrief getHtdocsDirPattern()
     *
     * @param $htdocsUrl @copybrief getHtdocsUrl()
     *
     * @param $disablePreferGz see AbstractUrlFactory::__construct()
     *
     * @param $disableAppendMtime see AbstractUrlFactory::__construct()
     */
    public function __construct(
        string $htdocsDirPattern,
        string $htdocsUrl,
        ?bool $disablePreferGz = null,
        ?bool $disableAppendMtime = null
    ) {
        $this->htdocsDirPattern_ =
            rtrim($htdocsDirPattern, DIRECTORY_SEPARATOR);

        $this->dirSegmentCount_ =
            substr_count($this->htdocsDirPattern_, DIRECTORY_SEPARATOR) + 1;

        $this->htdocsUrl_ = rtrim($htdocsUrl, '/');

        parent::__construct($disablePreferGz, $disableAppendMtime);
    }

    /// Real path of htdocs directory pattern, without trailing delimiter
    public function getHtdocsDirPattern(): string
    {
        return $this->htdocsDirPattern_;
    }

    /// URL pointing to htdocs directory, without trailing delimiter
    public function getHtdocsUrl(): string
    {
        return $this->htdocsUrl_;
    }

    /// @copydoc AbstractUrlFactory::createFromPath()
    public function createFromPath(string $path): string
    {
        $localPath = $this->createActualLocalPath($path);

        $localPathPrefix = implode(
            DIRECTORY_SEPARATOR,
            array_slice(
                explode(DIRECTORY_SEPARATOR, $localPath),
                0,
                $this->dirSegmentCount_
            )
        );

        /**
         * Replace a prefix matching the glob pattern $htdocsDirPattern_ with
         * $htdocsUrl_. If there is no such prefix, use $path unchanged.
         */
        if (fnmatch($this->htdocsDirPattern_, $localPathPrefix)) {
            $href = $this->htdocsUrl_
            . str_replace(
                DIRECTORY_SEPARATOR,
                '/',
                substr($localPath, strlen($localPathPrefix))
            );
        } else {
            $href = str_replace(DIRECTORY_SEPARATOR, '/', $path);
        }

        $href .= $this->createQuery($localPath);

        return $href;
    }
}
