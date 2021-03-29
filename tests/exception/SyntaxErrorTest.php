<?php

namespace alcamo\exception;

use PHPUnit\Framework\TestCase;

class SyntaxErrorTest extends TestCase
{
    /**
     * @dataProvider constructProvider
     */
    public function testConstruct(
        $text,
        $offset,
        $message,
        $code,
        $expectedMessage
    ) {
        $e = new SyntaxError($text, $offset, $message, $code);

        $this->assertEquals($text, $e->text);

        $this->assertEquals($offset, $e->offset);

        $this->assertSame($expectedMessage, $e->getMessage());

        $this->assertEquals($code, $e->getCode());
    }

    public function constructProvider(): array
    {
        return [
            'typical-use' => [
                'At vero# eos et accusam et justo duo dolores et ea rebum.',
                7,
                '',
                0,
                "Syntax error in \"At vero# eos et accusam et justo duo dol...\" at 7: \"# eos et a...\""
            ],

            'no-offset' => [
                'At vero eos et accusam et %justo duo dolores et ea rebum.',
                null,
                '',
                4711,
                "Syntax error in \"At vero eos et accusam et %justo duo dol...\""
            ],

            'custom-message' => [
                'no sea takimata',
                0,
                'First character must be uppercase',
                0,
                'First character must be uppercase'
            ],

            'extra-message' => [
                'labore et dolor*e magna aliquyam erat',
                15,
                '; asterisk not allowed',
                0,
                "Syntax error in \"labore et dolor*e magna aliquyam erat\" "
                . "at 15: \"*e magna a...\"; asterisk not allowed"
            ]
        ];
    }
}
