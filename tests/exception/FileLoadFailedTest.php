<?php

namespace alcamo\exception;

use PHPUnit\Framework\TestCase;

class FileLoadFailedTest extends TestCase
{
    /**
     * @dataProvider constructProvider
     */
    public function testConstruct(
        $filename,
        $message,
        $code,
        $expectedMessage
    ) {
        $e = new FileLoadFailed($filename, $message, $code);

        $this->assertSame($filename, $e->filename);

        $this->assertSame($expectedMessage, $e->getMessage());

        $this->assertEquals($code, $e->getCode());
    }

    public function constructProvider(): array
    {
        return [
            'typical-use' => [
                'foo.xml',
                null,
                null,
                "Failed to load file \"foo.xml\""
            ],

            'custom-message' => [
                'baz.json',
                'Lorem ipsum',
                42,
                'Lorem ipsum'
            ],

            'extra-message' => [
                'qux.ini',
                '; stet clita kasd gubergren',
                43,
                "Failed to load file \"qux.ini\"; stet clita kasd gubergren"
            ]
        ];
    }
}
