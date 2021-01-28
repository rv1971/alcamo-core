<?php

namespace alcamo\rdfa;

require dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR
  . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

try {
    $rdfaData = RdfaData::newFromIterable(json_decode($argv[1], true));
} catch (\Throwable $e) {
    echo $e->getMessage() . "\n";
    exit;
}

$rdfaData->alterSession();

echo session_cache_limiter() , "\n";
echo session_cache_expire() , "\n";
