<?php

namespace alcamo\exception;

use PHPUnit\Framework\TestCase;

class ReadonlyViolationTest extends TestCase
{
  /**
   * @dataProvider constructProvider
   */
    public function testConstruct(
        $object,
        $method,
        $message,
        $code,
        $expectedObject,
        $expectedMethod,
        $expectedMessage
    ) {
        $e = new ReadonlyViolation($object, $method, $message, $code);

        $this->assertSame($expectedObject ?? $this, $e->object);

        $this->assertSame($expectedMethod ?? __FUNCTION__, $e->method);

        $this->assertSame($expectedMessage, $e->getMessage());

        $this->assertEquals($code, $e->getCode());
    }

    public function constructProvider(): array
    {
        $object = new \DOMDocument();

        $message = 'Lorem ipsum dolor sit amet';

        return [
        'typical-use' => [
        null,
        null,
        null,
        null,
        null,
        null,
        "Attempt to modify readonly alcamo\\exception\\ReadonlyViolationTest object through testConstruct()"
        ],

        'separate-object' => [
        $object,
        __FUNCTION__,
        null,
        null,
        $object,
        __FUNCTION__,
        "Attempt to modify readonly DOMDocument object through constructProvider()"
        ],

        'custom-message' => [
        null,
        'fooBar',
        $message,
        null,
        null,
        'fooBar',
        $message
        ],

        'extra-message' => [
        null,
        null,
        '; consetetur sadipscing elitr',
        null,
        null,
        null,
        "Attempt to modify readonly alcamo\\exception\\ReadonlyViolationTest "
        . "object through testConstruct(); consetetur sadipscing elitr"
        ]
        ];
    }
}
