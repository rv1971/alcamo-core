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
                'dc:format' => 'application/json'
            ]
        );

        $response = new Response($rdfaData);

        $response->getBody()->write($bodyText);

        $this->assertSame($rdfaData, $response->getRdfaData());

        $this->assertSame($bodyText, (string)$response->getBody());

        $this->assertSame(strlen($bodyText), $response->computeContentLength());

        $this->assertSame(
            strlen($bodyText),
            $response->getRdfaData()['header:content-length']->getObject()
        );

        $this->assertSame(200, $response->getStatusCode());
    }
}
