<?php

namespace alcamo\html_creation;

abstract class AbstractUrlFactory implements UrlFactoryInterface {
  /// Whether to append the file modification timestamp as a GET parameter
  private $appendMtime_;

  public function __construct( ?bool $appendMtime = null ) {
    $this->appendMtime_ = (bool)$appendMtime;
  }

  public function getAppendMtime() : bool {
    return $this->appendMtime_;
  }

  public function createQuery( string $path ) : ?string {
    return $this->appendMtime_
      ? '?m=' . gmdate( 'YmdHis', filemtime( $path ) )
      : null;
  }

  abstract public function createFromPath( string $path ) : string;
}
