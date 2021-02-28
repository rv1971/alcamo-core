<?php

namespace alcamo\rdfa;

require getenv('PHPUNIT_COMPOSER_INSTALL');

try {
    $rdfaData = RdfaData::newFromIterable(json_decode($argv[1], true));
} catch (\Throwable $e) {
    echo $e->getMessage() . "\n";
    exit;
}

$rdfaData->alterSession();

echo session_cache_limiter() , "\n";
echo session_cache_expire() , "\n";
