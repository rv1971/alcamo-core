<?php

namespace alcamo\http;

use alcamo\rdfa\RdfaData;

require getenv('PHPUNIT_COMPOSER_INSTALL');

[ , $type, $text, $sendContentLength ] = $argv;

switch ($type) {
    case 'text':
        $response = Response::newFromStatusAndText(200, $text);

        break;

    case 'pipe':
        $stream = new PipeStream($text, "rt");

        $response = new Response(RdfaData::newFromIterable([]), $stream);

        break;
}

$response->emit($sendContentLength);
