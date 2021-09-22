<?php

namespace alcamo\exception;

use PHPUnit\Framework\TestCase;

class RecursionTest extends TestCase
{
    /**
     * @dataProvider constructProvider
     */
    public function testConstruct(
        $message,
        $code,
        $expectedMessage
    ) {
        $e = new Recursion($message, $code);

        $this->assertSame($expectedMessage, $e->getMessage());

        $this->assertEquals($code, $e->getCode());
    }

    public function constructProvider(): array
    {
        return [
            [
                'Recursion detected somewhere',
                42,
                'Recursion detected somewhere'
            ]
        ];
    }
}
