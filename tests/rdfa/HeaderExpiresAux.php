<?php

namespace alcamo\rdfa;

use alcamo\time\Duration;

require getenv('PHPUNIT_COMPOSER_INSTALL');

try {
    $header = new HeaderExpires(new Duration($argv[1]));
} catch (\Throwable $e) {
    echo $e->getMessage() . "\n";
    exit;
}

$header->alterSession();

echo session_cache_expire() , "\n";
