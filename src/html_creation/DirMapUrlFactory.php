<?php

namespace alcamo\html_creation;

use alcamo\exception\DirectoryNotFound;

class DirMapUrlFactory extends AbstractUrlFactory
{
  /// Real path of htdocs directory, without trailing delimiter.
    private $htdocsDir_;

  /// URL pointing to htdocs directory, without trailing delimiter.
    private $htdocsUrl_;

    public function __construct(
        string $htdocsDir,
        string $htdocsUrl,
        ?bool $appendMtime = null,
        ?bool $preferGz = null
    ) {
        $this->htdocsDir_ = realpath($htdocsDir);

        if (!$this->htdocsDir_) {
          /** @throw DirectoryNotFound if realpath of $htdocsDir culd not be
           *  obtained. */
            throw new DirectoryNotFound($htdocsDir);
        }

        $this->htdocsUrl_ = rtrim($htdocsUrl, '/');

        parent::__construct($appendMtime, $preferGz);
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
