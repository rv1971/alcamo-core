<?php

namespace alcamo\xml_creation;

use PHPUnit\Framework\TestCase;
use alcamo\exception\SyntaxError;

class CommentTest extends TestCase
{
    public function testBasics()
    {
        $text = 'Stet clita kasd gubergren, no sea takimata sanctus est Lorem '
            . 'ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur '
            . 'sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut '
            . 'labore et dolore magna aliquyam erat, sed diam voluptua. At '
            . 'vero eos et accusam et justo duo dolores et ea rebum. Stet '
            . 'clita kasd gubergren, no sea takimata sanctus est Lorem ipsum '
            . 'dolor sit amet. Lorem ipsum dolor sit amet, consetetur '
            . 'sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut '
            . 'labore et dolore magna aliquyam erat, sed diam voluptua. At '
            . 'vero eos et accusam et justo duo dolores et ea rebum. Stet '
            . 'clita kasd gubergren, no sea takimata sanctus est Lorem ipsum '
            . 'dolor sit amet. Lorem ipsum dolor sit amet, consetetur '
            . 'sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut '
            . 'labore et dolore magna aliquyam erat, sed diam voluptua. At '
            . 'vero eos et accusam et justo duo dolores et ea rebum. Stet '
            . 'clita kasd gubergren, no sea takimata sanctus est Lorem ipsum '
            . 'dolor sit amet.';

        $comment = new Comment($text);

        $this->assertSame($text, $comment->getContent());

        $this->assertEquals("<!-- $text -->", (string)$comment);
    }

    public function testException()
    {
        $text = 'Stet clita kasd gubergren -- no sea takimata sanctus est Lorem ipsum dolor sit amet.';

        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage(
            'Syntax error in "'
            . substr($text, 0, 40)
            . '..." at 26: "-- no sea ..."; double-hyphen in XML comment'
        );

        $comment = new Comment($text);
    }
}
