<?php

namespace alcamo\exception;

use PHPUnit\Framework\TestCase;

class DirectoryNotFoundTest extends TestCase
{
  /**
   * @dataProvider constructProvider
   */
    public function testConstruct(
        $path,
        $message,
        $code,
        $expectedMessage
    ) {
        $e = new DirectoryNotFound($path, $message, $code);

        $this->assertSame($path, $e->path);

        $this->assertSame($expectedMessage, $e->getMessage());

        $this->assertEquals($code, $e->getCode());
    }

    public function constructProvider(): array
    {
        return [
        'typical-use' => [
        'foo/bar',
        '',
        0,
        "Directory \"foo/bar\" not found"
        ],

        'custom-message' => [
        'baz',
        'Lorem ipsum',
        42,
        'Lorem ipsum'
        ],

        'extra-message' => [
        'qux/quux',
        '; stet clita kasd gubergren',
        43,
        "Directory \"qux/quux\" not found; stet clita kasd gubergren"
        ]
        ];
    }
}
