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

    /**
     * @dataProvider newFromStatusAndTextProvider
     */
    public function testNewFromStatusAndText(
        $status,
        $text,
        $rdfaData,
        $expectedText,
        $expectedRdfaData
    ) {
        $response = Response::newFromStatusAndText(
            $status,
            $text,
            isset($rdfaData) ? RdfaData::newFromIterable($rdfaData) : null
        );

        $this->assertSame($status, $response->getStatusCode());
        $this->assertSame($expectedText, (string)$response->getBody());

        $this->assertEquals(
            RdfaData::newFromIterable($expectedRdfaData),
            $response->getRdfaData()
        );
    }

    public function newFromStatusAndTextProvider()
    {
        return [
            'simple' => [
                404,
                null,
                null,
                'Not Found',
                [ 'dc:format' => 'text/plain' ]
            ],
            'text-and-rdfa' => [
                200,
                'Lorem ipsum',
                [ 'dc:format' => 'text/plain; charset=us-ascii' ],
                'Lorem ipsum',
                [ 'dc:format' => 'text/plain; charset="us-ascii"' ],
            ]
        ];
    }
}
