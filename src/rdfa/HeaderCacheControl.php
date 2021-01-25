<?php

namespace alcamo\rdfa;

/**
 * @sa [Caching](https://tools.ietf.org/html/rfc7234)
 */
class HeaderCacheControl extends AbstractEnumeratorStmt {
  use NoHtmlTrait;

  const PROPERTY = 'header:cache-control';

  const VALUES = [ 'public', 'private', 'no-cache' ];

  /// Set session parameters accordingly.
  public function alterSession() {
    switch ( (string)$this ) {
    case 'no-cache':
      session_cache_limiter( 'nocache' );
      break;

    default:
      session_cache_limiter( (string)$this );
    }
  }
}
