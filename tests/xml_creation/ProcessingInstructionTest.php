<?php

namespace alcamo\xml_creation;

use PHPUnit\Framework\TestCase;
use alcamo\exception\SyntaxError;

class ProcessingInstructionTest extends TestCase
{
    public function testBasics()
    {
        $text = 'At vero eos et accusam et justo duo dolores et ea rebum.';

        $target = 'foo';

        $pi = new ProcessingInstruction($target, $text);

        $this->assertSame($target, $pi->getTarget());

        $this->assertSame($text, $pi->getContent());

        $this->assertEquals("<?foo $text?>", (string)$pi);
    }

  /**
   * @dataProvider targetExceptionProvider
   */
    public function testTargetException($target, $content, $expectedMessage)
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage($expectedMessage);

        new ProcessingInstruction($target, $content);
    }

    public function targetExceptionProvider()
    {
        $content = 'bar="baz"';

        return [
            [
                '123',
                $content,
                "Syntax error in \"123\"; not a valid XML PI target"
            ]
        ];
    }

    public function testContentException()
    {
        $text = 'dolor sit amet ?>';

        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage(
            'Syntax error in "'
            . $text
            . '" at 15: "?>"; "?>" in XML PI'
        );

        $pi = new ProcessingInstruction('bar', $text);
    }
}
