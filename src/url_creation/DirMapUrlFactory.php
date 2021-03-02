<?php

namespace alcamo\url_creation;

use alcamo\exception\DirectoryNotFound;

class DirMapUrlFactory extends AbstractUrlFactory
{
    /// Real path of htdocs directory, without trailing delimiter.
    private $htdocsDir_;

    /// URL pointing to htdocs directory, without trailing delimiter.
    private $htdocsUrl_;

    public function newFromConf(array $conf): self
    {
        return new self(
            $conf['htdocsDir'],
            $conf['htdocsUrl'],
            $conf['disablePreferGz'] ?? null,
            $conf['disableAppendMtime'] ?? null
        );
    }

    public function __construct(
        string $htdocsDir,
        string $htdocsUrl,
        ?bool $disablePreferGz = null,
        ?bool $disableAppendMtime = null
    ) {
        $this->htdocsDir_ = realpath($htdocsDir);

        if (!$this->htdocsDir_) {
          /** @throw DirectoryNotFound if realpath of $htdocsDir culd not be
           *  obtained. */
            throw new DirectoryNotFound($htdocsDir);
        }

        $this->htdocsUrl_ = rtrim($htdocsUrl, '/');

        parent::__construct($disablePreferGz, $disableAppendMtime);
    }

    public function getHtdocsDir(): string
    {
        return $this->htdocsDir_;
    }

    public function getHtdocsUrl(): string
    {
        return $this->htdocsUrl_;
    }

    public function createFromPath(string $path): string
    {
        $realpath = $this->realpath($path);

      /**
       * Replace a prefix corresponding to $htdocsDir_ with $htdocsUrl_. If
       * there is no such prefix, use $path unchanged.
       */
        if (
            substr($realpath, 0, strlen($this->htdocsDir_)) == $this->htdocsDir_
        ) {
            $href = $this->htdocsUrl_
            . str_replace(
                DIRECTORY_SEPARATOR,
                '/',
                substr($realpath, strlen($this->htdocsDir_))
            );
        } else {
            $href = $path;
        }

        $href .= $this->createQuery($realpath);

        return $href;
    }
}
