<?php

namespace alcamo\rdfa;

use alcamo\time\Duration;

require dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR
  . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

try {
    $header = new HeaderExpires(new Duration($argv[1]));
} catch (\Throwable $e) {
    echo $e->getMessage() . "\n";
    exit;
}

$header->alterSession();

echo session_cache_expire() , "\n";
