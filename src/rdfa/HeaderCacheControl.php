<?php

namespace alcamo\rdfa;

/**
 * @sa [Caching](https://tools.ietf.org/html/rfc7234)
 */
class HeaderCacheControl extends AbstractStmt
{
    use LiteralContentTrait;
    use NoHtmlTrait;
    use NoPrefixMapTrait;

    public const PROPERTY_CURIE = 'header:cache-control';
    public const HTTP_HEADER    = 'Cache-Control';

    /// Set session parameters accordingly.
    public function alterSession()
    {
        switch ((string)$this) {
            case 'no-cache':
                session_cache_limiter('nocache');
                break;

            default:
                session_cache_limiter((string)$this);
        }
    }
}
