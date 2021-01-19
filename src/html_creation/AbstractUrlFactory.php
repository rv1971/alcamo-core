<?php

namespace alcamo\html_creation;

use alcamo\exception\FileNotFound;

abstract class AbstractUrlFactory implements UrlFactoryInterface {
  /// Whether to append the file modification timestamp as a GET parameter
  private $appendMtime_;

  /// Whether to prefer a gzipped version, if available
  private $preferGz_;

  public function __construct(
    ?bool $appendMtime = null, ?bool $preferGz = null
  ) {
    $this->appendMtime_ = (bool)$appendMtime;
    $this->preferGz_ = (bool)$preferGz;
  }

  public function getAppendMtime() : bool {
    return $this->appendMtime_;
  }

  public function getPreferGz() : bool {
    return $this->preferGz_;
  }

  public function realpath( string $path ) : string {
    $realpath = realpath( $path );

    if ( !$realpath ) {
      /** @throw FileNotFound if realpath of $path culd not be obtained. */
      throw new FileNotFound( $path );
    }

    /* The gzipped file has the additional suffix .gz except for SVG files
     * where the suffix .svg becomes .svgz. */
    $gzPath =
      substr( $realpath, -4 ) == '.svg' ? "${realpath}z" : "$realpath.gz";

    if ( $this->preferGz_ && is_readable( $gzPath ) ) {
      return $gzPath;
    }

    return $realpath;
  }

  public function createQuery( string $path ) : ?string {
    return $this->appendMtime_
      ? '?m=' . gmdate( 'YmdHis', filemtime( $path ) )
      : null;
  }

  abstract public function createFromPath( string $path ) : string;
}
