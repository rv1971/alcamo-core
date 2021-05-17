<?php

namespace alcamo\exception;

use PHPUnit\Framework\TestCase;

class PopenFailedTest extends TestCase
{
    /**
     * @dataProvider constructProvider
     */
    public function testConstruct(
        $command,
        $mode,
        $message,
        $code,
        $expectedMessage
    ) {
        $e = new PopenFailed($command, $mode, $message, $code);

        $this->assertSame($command, $e->command);

        $this->assertSame($mode, $e->mode);

        $this->assertSame($expectedMessage, $e->getMessage());

        $this->assertEquals($code, $e->getCode());
    }

    public function constructProvider(): array
    {
        return [
            'typical-use' => [
                '/usr/bin/foo --bar',
                'rb',
                '',
                0,
                'Failed to open process "/usr/bin/foo --bar" in mode "rb"'
            ],

            'custom-message' => [
                '/usr/bin/foo --bar',
                'rb',
                'Lorem ipsum',
                42,
                'Lorem ipsum'
            ],

            'extra-message' => [
                ['/usr/sbin/bar', '--baz=qux' ],
               null,
                '; stet clita kasd gubergren',
                43,
                'Failed to open process "/usr/sbin/bar --baz=qux"'
                . '; stet clita kasd gubergren'
            ]
        ];
    }
}
