<?php

namespace alcamo\http;

use PHPUnit\Framework\TestCase;
use alcamo\rdfa\RdfaData;

class ResponseTest extends TestCase
{
    public function testConstruct()
    {
        $bodyText = '{ "msg": "Hello, World!" }';

        $rdfaData = RdfaData::newFromIterable(
            [
                'dc:format' => 'application/json',
                'header:content-length' => strlen($bodyText)
            ]
        );

        $response = new Response($rdfaData);

        $response->getBody()->write($bodyText);

        $this->assertSame($rdfaData, $response->getRdfaData());

        $this->assertSame($bodyText, (string)$response->getBody());

        $this->assertSame(200, $response->getStatusCode());
    }
}
