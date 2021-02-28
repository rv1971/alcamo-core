<?php

namespace alcamo\rdfa;

use PHPUnit\Framework\TestCase;

class HeaderExpiresTest extends TestCase
{
  /**
   * @dataProvider basicsProvider
   */
    public function testBasics($value, $expected)
    {
        exec(
            'PHPUNIT_COMPOSER_INSTALL="' . PHPUNIT_COMPOSER_INSTALL . '" php '
            . __DIR__ . DIRECTORY_SEPARATOR . "HeaderExpiresAux.php $value",
            $output
        );

        $this->assertSame($expected, $output[0]);
    }

    public function basicsProvider()
    {
        return [
            [ 'PT10M', '10' ],
            [ 'P1D', '1440' ]
        ];
    }
}
