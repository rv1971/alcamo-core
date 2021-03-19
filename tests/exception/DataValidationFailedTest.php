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
        $line,
        $message,
        $code,
        $expectedMessage
    ) {
        $e = new DataValidationFailed($data, $uri, $line, $message, $code);

        $this->assertSame($data, $e->data);

        $this->assertSame($uri, $e->uri);

        $this->assertSame($line, $e->line);

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
                null,
                "Failed to validate \"At vero eoos\""
            ],

            'custom-message' => [
                'At vero eoos',
                null,
                null,
                'Lorem ipsum',
                42,
                'Lorem ipsum'
            ],

            'line-only' => [
                'At vero eoos',
                null,
                4242,
                null,
                null,
                "Failed to validate \"At vero eoos\", line 4242"
            ],

            'extra-message' => [
                'At vero eoos et accusam et justo duo dolores et ea rebum.',
                'https://www.example.org/lorem',
                1234,
                '; stet clita kasd gubergren',
                43,
                'Failed to validate '
                . '"At vero eoos et accusam et justo duo dol..." '
                . 'at https://www.example.org/lorem, line 1234; '
                . 'stet clita kasd gubergren'
            ]
        ];
    }
}
