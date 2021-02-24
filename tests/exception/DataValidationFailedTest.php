<?php

namespace alcamo\exception;

use PHPUnit\Framework\TestCase;

class DataValidationFailedTest extends TestCase
{
    /**
     * @dataProvider constructProvider
     */
    public function testConstruct(
        $data,
        $uri,
        $message,
        $code,
        $expectedMessage
    ) {
        $e = new DataValidationFailed($data, $uri, $message, $code);

        $this->assertSame($data, $e->data);

        $this->assertSame($uri, $e->uri);

        $this->assertSame($expectedMessage, $e->getMessage());

        $this->assertEquals($code, $e->getCode());
    }

    public function constructProvider(): array
    {
        return [
            'typical-use' => [
                'At vero eoos',
                null,
                null,
                null,
                "Failed to validate \"At vero eoos\""
            ],

            'custom-message' => [
                'At vero eoos',
                null,
                'Lorem ipsum',
                42,
                'Lorem ipsum'
            ],

            'extra-message' => [
                'At vero eoos et accusam et justo duo dolores et ea rebum.',
                'https://www.example.org/lorem',
                '; stet clita kasd gubergren',
                43,
                'Failed to validate '
                . '"At vero eoos et accusam et justo duo dol..." '
                . 'at https://www.example.org/lorem; stet clita kasd gubergren'
            ]
        ];
    }
}
