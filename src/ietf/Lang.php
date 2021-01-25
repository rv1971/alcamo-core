<?php

namespace alcamo\ietf;

use alcamo\exception\{SyntaxError};

/**
 * @brief Language as in
 * [RFC4646](http://tools.ietf.org/html/rfc4646).
 *
 * @invariant Immutable class.
 *
 * Only supports ISO 639 primary tags and ISO 3166-1 region subtags.
 */
class Lang {
  const PRIMARY_TAG_REGEXP = '/^[a-z]{2,3}$/';
  const REGION_TAG_REGEXP  = '/^[A-Z]{2}$/';

  private $primary_; ///< Primary tag.
  private $region_;  ///< Region subtag.

  public static function newFromString( string $string ) {
    if ( isset( $string[2] ) ) {
      return new self( substr( $string, 0, 2 ), substr( $string, 3 ) );
    } else {
      return new self( $string );
    }
  }

  public function __construct( string $primary, ?string $region = null ) {
    if ( !preg_match( static::PRIMARY_TAG_REGEXP, $primary ) ) {
      throw new SyntaxError( $primary, null, '; not a valid ISO 639 language' );
    }

    $this->primary_ = $primary;

    if ( isset( $region ) ) {
      if ( !preg_match( static::REGION_TAG_REGEXP, $region ) ) {
        throw new SyntaxError(
          $region, null, '; not a valid ISO 3166-1 alpha-2 code' );
      }

      $this->region_ = $region;
    }
  }

  public function getPrimary() : string {
    return $this->primary_;
  }

  public function getRegion() : ?string {
    return $this->region_;
  }

  /// Convert to [RFC 4646](http://tools.ietf.org/html/rfc4646) representation.
  function __toString()
  {
    return isset( $this->region_ )
      ? "{$this->primary_}-{$this->region_}" : $this->primary_;
  }
}
