<?php

namespace alcamo\exception;

use PHPUnit\Framework\TestCase;

class MethodNotFoundTest extends TestCase
{
  /**
   * @dataProvider constructProvider
   */
    public function testConstruct(
        $objectOrLabel,
        $method,
        $message,
        $code,
        $expectedMessage
    ) {
        $e = new MethodNotFound($objectOrLabel, $method, $message, $code);

        $this->assertSame($objectOrLabel, $e->objectOrLabel);

        $this->assertSame($method, $e->method);

        $this->assertSame($expectedMessage, $e->getMessage());

        $this->assertEquals($code, $e->getCode());
    }

    public function constructProvider(): array
    {
        return [
            'typical-use' => [
                $this,
                'foo',
                '',
                0,
                'Method "foo" not found in ' . self::class
            ],

            'custom-message' => [
                'special object',
                'baz',
                'At vero eos et accusam',
                43,
                'At vero eos et accusam'
            ],

            'extra-message' => [
                'foo',
                'bar',
                '; at vero eos et accusam',
                44,
                'Method "bar" not found in foo; at vero eos et accusam'
            ]
        ];
    }
}
