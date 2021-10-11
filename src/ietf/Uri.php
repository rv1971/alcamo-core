<?php

namespace alcamo\ietf;

use GuzzleHttp\Psr7\Uri as GuzzleHttpUri;

/**
 * @namespace alcamo::ietf
 *
 * @brief Classes to model data specified by the ietf
 */

/**
 * @brief PSR-compliant URI class
 *
 * Currently identical to the GuzzleHttp implementation. This might change in
 * the future.
 *
 * The `Laminas\Diactoros` implementation of Uri is not suitable because it
 * does not support `file:/` URIs.
 */
class Uri extends GuzzleHttpUri
{
}
