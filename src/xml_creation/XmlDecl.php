<?php

namespace alcamo\xml_creation;

use alcamo\exception\SyntaxError;

/// XML declaration
class XmlDecl implements NodeInterface {
  const ENCODING_REGEXP = '/^[A-Za-z][-A-Za-z0-9._]*$/';
  const VERSION_REGEXP = '/^1.\d+$/';

  protected $version_;
  protected $encoding_;
  protected $stanalone_; ///< Boolean

  function __construct(
    ?string $version = null, ?string $encoding = null, ?bool $standalone = null
  ) {
    if (
      isset( $version ) && !preg_match( self::VERSION_REGEXP, $version )
    ) {
      /** @throw SyntaxError if $version is not a valid version. */
      throw new SyntaxError( $version, null, '; not a valid XML version' );
    }

    $this->version_ = $version ?? '1.0';

    if (
      isset( $encoding ) && !preg_match( self::ENCODING_REGEXP, $encoding )
    ) {
      /** @throw SyntaxError if $encoding is not a valid encoding. */
      throw new SyntaxError( $encoding, null, '; not a valid XML encoding' );
    }

    $this->encoding_ = $encoding ?? 'UTF-8';

    $this->standalone_ = $standalone ?? false;
  }

  public function getContent() {
    return null;
  }

  public function getVersion() {
    return $this->version_;
  }

  public function getEncoding() {
    return $this->encoding_;
  }

  public function getStandalone() {
    return $this->standalone_;
  }

  function __toString() {
    $result =
      "<?xml version=\"{$this->version_}\" encoding=\"{$this->encoding_}\"";

    if ( $this->standalone_ ) {
      $result .= ' standalone="yes"';
    }

    return "$result?>";
  }
}
