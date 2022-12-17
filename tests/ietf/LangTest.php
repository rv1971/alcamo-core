<?php

namespace alcamo\ietf;

use PHPUnit\Framework\TestCase;
use alcamo\exception\{SyntaxError};

class LangTest extends TestCase
{
  /**
   * @dataProvider constructProvider
   */
    public function testConstruct($primary, $region, $private, $expectedString)
    {
        $lang = new Lang($primary, $region, $private);

        $this->assertSame($primary, $lang->getPrimary());
        $this->assertSame($region, $lang->getRegion());
        $this->assertSame($private, $lang->getPrivate());

        $this->assertEquals($expectedString, (string)$lang);
    }

    public function constructProvider()
    {
        return [
            'without-region' => [ 'it', null, null, 'it' ],
            'with-region' => [ 'en', 'US', null, 'en-US' ],
            'with-private' => [ 'fr', null, 'local', 'fr-x-local' ],
            'with-region-and-private' => [
                'de',
                'CH',
                'simple',
                'de-CH-x-simple'
            ],
            'three-letters-primary' => [
                'ach',
                'UG',
                '2nd-gen-refugee',
                'ach-UG-x-2nd-gen-refugee'
            ]
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

    public function testConstructPrivateException()
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage(
            'Syntax error in "@"; not a valid privateuse tag'
        );

        $comment = new Lang('en', null, '@');
    }

  /**
   * @dataProvider newFromStringProvider
   */
    public function testNewFromString(
        $string,
        $expectedPrimary,
        $expectedRegion,
        $expectedPrivate
    ) {
        $lang = Lang::newFromString($string);

        $this->assertSame($expectedPrimary, $lang->getPrimary());
        $this->assertSame($expectedRegion, $lang->getRegion());
        $this->assertSame($expectedPrivate, $lang->getPrivate());
    }

    public function newFromStringProvider()
    {
        return [
            'without-region' => [ 'ee', 'ee', null, null ],
            'with-region' => [ 'es-EC', 'es', 'EC', null ],
            'with-private' => [ 'cn-x-local', 'cn', null, 'local' ],
            'with-region-and-private' => [
                'ru-KZ-x-south-east',
                'ru',
                'KZ',
                'south-east'
            ]
        ];
    }

    public function testNewFromLocale()
    {
        setlocale(LC_ALL, 'de_CH.ISO-8859-1');

        $this->assertEquals(
            new Lang('de', 'CH'),
            Lang::newFromLocale()
        );

        $this->assertEquals(
            new Lang('oc'),
            Lang::newFromLocale('oc')
        );
    }

    public function testNewFromStringException1()
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage(
            'Syntax error in "1996"; not a valid privateuse tag'
        );

        Lang::newFromString('de-CH-1996');
    }

    public function testNewFromStringException2()
    {
        $this->expectException(SyntaxError::class);
        $this->expectExceptionMessage(
            'Syntax error in "x"; not a valid privateuse tag'
        );

        Lang::newFromString('pt-x');
    }
}
