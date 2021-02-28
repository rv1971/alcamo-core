<?php

namespace alcamo\rdfa;

require getenv('PHPUNIT_COMPOSER_INSTALL');

try {
    $header = new HeaderCacheControl($argv[1]);
} catch (\Throwable $e) {
    echo $e->getMessage() . "\n";
    exit;
}

$header->alterSession();

echo session_cache_limiter() , "\n";
