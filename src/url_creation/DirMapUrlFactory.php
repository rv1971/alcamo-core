<?php

namespace alcamo\url_creation;

use alcamo\exception\DirectoryNotFound;

/**
 * @brief Factory mapping a htdocs directory to a htdocs URL
 *
 * @date Last reviewed 2021-06-15
 */
class DirMapUrlFactory extends AbstractUrlFactory
{
    private $htdocsDir_; ///< string
    private $htdocsUrl_; ///< string

    /**
     * @brief Create from associative array ot ArrayAccess object
     *
     * @param $conf array|ArrayAccess must contain the keys `htdocsDir` and
     * `htdocsUrl`, may contain the keys `disablePreferGz` and
     * `disableAppendMtime`
     */
    public function newFromConf($conf): self
    {
        return new self(
            $conf['htdocsDir'],
            $conf['htdocsUrl'],
            $conf['disablePreferGz'] ?? null,
            $conf['disableAppendMtime'] ?? null
        );
    }

    /**
     * @param $htdocsDir @copybrief getHtdocsDir()
     *
     * @param $htdocsUrl @copybrief getHtdocsUrl()
     *
     * @param $disablePreferGz see AbstractUrlFactory::__construct()
     *
     * @param $disableAppendMtime see AbstractUrlFactory::__construct()
     */
    public function __construct(
        string $htdocsDir,
        string $htdocsUrl,
        ?bool $disablePreferGz = null,
        ?bool $disableAppendMtime = null
    ) {
        $this->htdocsDir_ = realpath($htdocsDir);

        if (!$this->htdocsDir_) {
          /** @throw alcamo::exception::DirectoryNotFound if
           *  `realpath($htdocsDir)` fails. */
            throw (new DirectoryNotFound())
                ->setMessageContext([ 'path' => $htdocsDir ]);
        }

        $this->htdocsUrl_ = rtrim($htdocsUrl, '/');

        parent::__construct($disablePreferGz, $disableAppendMtime);
    }

    /// Real path of htdocs directory, without trailing delimiter
    public function getHtdocsDir(): string
    {
        return $this->htdocsDir_;
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

        /**
         * Replace a prefix corresponding to $htdocsDir_ with $htdocsUrl_. If
         * there is no such prefix, use $path unchanged.
         */
        if (
            substr($localPath, 0, strlen($this->htdocsDir_))
            == $this->htdocsDir_
        ) {
            $href = $this->htdocsUrl_
            . str_replace(
                DIRECTORY_SEPARATOR,
                '/',
                substr($localPath, strlen($this->htdocsDir_))
            );
        } else {
            $href = str_replace(DIRECTORY_SEPARATOR, '/', $path);
        }

        $href .= $this->createQuery($localPath);

        return $href;
    }
}
