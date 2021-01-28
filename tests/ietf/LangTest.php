<?php

namespace alcamo\ietf;

use PHPUnit\Framework\TestCase;
use alcamo\exception\{SyntaxError};

class LangTest extends TestCase
{
  /**
   * @dataProvider constructProvider
   */
    public function testConstruct($primary, $region, $expectedString)
    {
        $lang = new Lang($primary, $region);

        $this->assertSame($primary, $lang->getPrimary());
        $this->assertSame($region, $lang->getRegion());

        $this->assertEquals($expectedString, (string)$lang);
    }

    public function constructProvider()
    {
        return [
        'without-region' => [ 'it', null, 'it' ],
        'with-region' => [ 'en', 'US', 'en-US' ]
        ];
    }

    public function testConstructPrimaryException()
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage(
            'Syntax error in "hajara"; not a valid ISO 639 language'
        );

        $comment = new Lang('hajara');
    }

    public function testConstructRegionException()
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage(
            'Syntax error in "X1"; not a valid ISO 3166-1 alpha-2 code'
        );

        $comment = new Lang('en', 'X1');
    }

  /**
   * @dataProvider newFromStringProvider
   */
    public function testNewFromString(
        $string,
        $expectedPrimary,
        $expectedRegion
    ) {
        $lang = Lang::newFromString($string);

        $this->assertSame($expectedPrimary, $lang->getPrimary());
        $this->assertSame($expectedRegion, $lang->getRegion());
    }

    public function newFromStringProvider()
    {
        return [
        'without-region' => [ 'ee', 'ee', null ],
        'with-region' => [ 'es-EC', 'es', 'EC' ]
        ];
    }
}
