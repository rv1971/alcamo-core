<?php

namespace alcamo\iana;

use alcamo\exception\{FileNotFound, InvalidEnumerator, SyntaxError};

/**
 * @brief Media type.
 *
 * @invariant Immutable class.
 *
 * @sa [RFC 2045, section 5](https://tools.ietf.org/html/rfc2045#section-5)
 * @sa [RFC 2046](https://tools.ietf.org/html/rfc2046)
 * @sa [RFC 6838](https://tools.ietf.org/html/rfc6838)
 * @sa [RFC 6839](https://tools.ietf.org/html/rfc6839)
 */
class MediaType {
  const TOP_LEVEL_TYPES = [
    'text', 'image', 'audio', 'video', 'application', 'message', 'multipart'
  ];

  /// @sa [RFC 2045, section 5](https://tools.ietf.org/html/rfc2045#section-5)
  const TSPECIALS = '()<>@,;:\\\\"\/\[\]?=';

  /// @sa [RFC 2045, section 5](https://tools.ietf.org/html/rfc2045#section-5)
  const TOKEN = '[^\x00-\x20' . self::TSPECIALS . ']+';

  /// @sa [RFC 822, section 3.3](https://tools.ietf.org/html/rfc822#section-3.3)
  const LINEAR_WHITE_SPACE = '\r\n[\t ]';

  /// @sa [RFC 822, section 3.3](https://tools.ietf.org/html/rfc822#section-3.3)
  const QTEXT = '[^\r"\\\\]|\r\n[\t ]';

  /// @sa [RFC 822, section 3.3](https://tools.ietf.org/html/rfc822#section-3.3)
  const QUOTED_PAIR = '\\\\.';

  /// @sa [RFC 822, section 3.3](https://tools.ietf.org/html/rfc822#section-3.3)
  const QUOTED_STRING = '"(?:' . self::QTEXT . '|' . self::QUOTED_PAIR . ')*"';

  /// @sa [RFC 2045, section 5](https://tools.ietf.org/html/rfc2045#section-5)
  const VALUE = self::TOKEN . '|' . self::QUOTED_STRING;

  /// @sa [RFC 2045, section 5](https://tools.ietf.org/html/rfc2045#section-5)
  const TYPE_SUBTYPE_REGEXP =
    '/^\s*(' . self::TOKEN . ')\s*\/\s*(' . self::TOKEN . ')\s*/';

  /// @sa [RFC 2045, section 5](https://tools.ietf.org/html/rfc2045#section-5)
  const PARAM_REGEXP =
    '/^;\s*(' . self::TOKEN . ')\s*=\s*(' . self::VALUE . ')\s*/';

  private $type_;           ///< Top-level type.
  private $subtype_;        ///< Complete subtype.
  private $representation_; ///< Representation subtype, if any.
  private $params_ = [];    ///< Array of parameters.

  public static function newFromString( string $string ) : self {
    if ( !preg_match( self::TYPE_SUBTYPE_REGEXP, $string, $matches ) ) {
      /** @throw SyntaxError if $string is not a valid media type. */
      throw new SyntaxError( $string, null, '; not a valid media type' );
    }

    $type = $matches[1];
    $subtype = $matches[2];

    $params = [];

    for (
      $string = substr( $string, strlen( $matches[0] ) );
      $string;
      $string = substr( $string, strlen( $matches[0] ) )
    ) {
      if ( !preg_match( self::PARAM_REGEXP, $string, $matches ) ) {
        /** @throw SyntaxError if next piece of $string is not a valid
         *  parameter. */
        throw new SyntaxError(
          $string, 0, '; not a valid media type parameter' );
      }

      $params[$matches[1]] = $matches[2][0] == '"'
        ? preg_replace(
          [ '/\\\\([^\\\\])/', '/\r\n[\t ]/' ],
          [ '\1', ' ' ],
          substr( $matches[2], 1, strlen( $matches[2] ) - 2 )
        )
        : $matches[2];
    }

    return new self( $type, $subtype, $params );
  }

  public static function newFromFilename( $filename ) {
    if ( !is_readable( $filename ) ) {
      /** @throw FileNotFound if the file is unreadable. */
      throw new FileNotFound( $filename );
    }

    $finfo = new \finfo();

    $mediaType = self::newFromString(
      $finfo->file( $filename, FILEINFO_MIME )
    );

    switch ( $mediaType->getType() ) {
      case 'text':
        // finfo does not recognize CSS and JS
        switch ( pathinfo( $filename, PATHINFO_EXTENSION ) ) {
          case 'css':
            $mediaType = new self( 'text', 'css', $mediaType->getParams() );
            break;

          case 'js':
            $mediaType = new self( 'application', 'javascript' );
            break;

          case 'json':
            $mediaType = new self( 'application', 'json' );
            break;
        }

        break;

      case 'image':
        unset( $mediaType->params_['charset'] );

        switch ( $mediaType->getSubtype() ) {
          case 'x-icon':
            $mediaType = new self( 'image', 'vnd.microsoft.icon' );
            break;
        }

        break;

      default:
        unset( $mediaType->params_['charset'] );
    }

    return $mediaType;
  }

  /**
   * All type values, subtype values and parameter names are folded to
   * lowercase.
   */
  function __construct(
    string $type, string $subtype, ?iterable $params = null
  ) {
    $type = strtolower( $type );

    if ( !in_array( $type, self::TOP_LEVEL_TYPES ) ) {
      /** @throw InvalidEnumerator if the top-level type is invalid. */
      throw new InvalidEnumerator(
        $type, self::TOP_LEVEL_TYPES, '; not a valid top-level media type' );
    }

    $this->type_ = $type;
    $this->subtype_ = strtolower( $subtype );

    $plusPos = strpos( $this->subtype_, '+' );

    if ( $plusPos !== false ) {
      $this->representation_ = substr( $this->subtype_, $plusPos + 1 );
    }

    if ( isset( $params ) ) {
      foreach ( $params as $attr => $value )  {
        $this->params_[strtolower( $attr )] = $value;
      }
    }
  }

  public function getType() : string {
    return $this->type_;
  }

  public function getSubtype() : string {
      return $this->subtype_;
  }

  public function getRepresentation() : ?string {
    return $this->representation_;
  }

  public function getParams() : array {
    return $this->params_;
  }

  /**
   * @return Media type representation in lowercase with all parameters as
   * quoted strings, without folding.
   */
  function __toString() {
    $result = "{$this->type_}/{$this->subtype_}";

    foreach ( $this->params_ as $attr => $value ) {
      /* Replace `\` first to avoid applying it to the `\"` resulting from the
       * replacement of `"`. */
      $result .= "; $attr=\""
        . str_replace( [ '\\', '"', "\r" ], [ '\\\\', '\"', '\r' ], $value )
        . '"';
    }

    return $result;
  }
}
