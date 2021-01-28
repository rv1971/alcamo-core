<?php

namespace alcamo\rdfa;

require dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR
  . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

try {
    $header = new HeaderCacheControl($argv[1]);
} catch (\Throwable $e) {
    echo $e->getMessage() . "\n";
    exit;
}

$header->alterSession();

echo session_cache_limiter() , "\n";
