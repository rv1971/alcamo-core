<?php

namespace alcamo\html_creation;

use PHPUnit\Framework\TestCase;
use alcamo\modular_class\ModuleTrait;
use alcamo\xml_creation\{Comment, Nodes};

class FooModule
{
    use ModuleTrait;

    public const NAME = 'foo';

    public $text = 'ut labore et dolore magna aliquyam erat';
}

class FactoryTest extends TestCase
{
    public function testConstruct()
    {
        $pageFactory = new PageFactory(
            new ResourceFactory(
                new DirMapUrlFactory(__DIR__, '/content')
            )
        );

        $factory = Factory::newFromRdfaData(
            [],
            [ 'qux' => 'quux' ],
            [ new FooModule(), $pageFactory ],
            new DirMapUrlFactory(__DIR__, 'foo-bar')
        );

        $this->assertSame('foo-bar', $factory->getUrlFactory()->getHtdocsUrl());

        $this->assertSame('quux', $factory->getConf()['qux']);

        $this->assertSame(
            'ut labore et dolore magna aliquyam erat',
            $factory['foo']->text
        );

        $this->assertSame(
            '/content',
            $factory['page']->getResourceFactory()->getUrlFactory()
                ->getHtdocsUrl()
        );
    }

    /**
     * @dataProvider htmlGenerationProvider
     */
    public function testHtmlGeneration(
        $rdfaData,
        $conf,
        $resources,
        $extraHeadNodes,
        $expectedHtml
    ) {
        $factory = Factory::newFromRdfaData($rdfaData, $conf);

        $html = $factory['page']->createBegin($resources, $extraHeadNodes)
            . 'Lorem ipsum.'
            . $factory['page']->createEnd();

        $maskedHtml = preg_replace('/\\.\\d{6}s -->/', '.123456s -->', $html);

        $this->assertSame($expectedHtml, $maskedHtml);
    }

    public function htmlGenerationProvider()
    {
        $cssPath = __DIR__ . DIRECTORY_SEPARATOR
            . 'element' . DIRECTORY_SEPARATOR . 'alcamo.css';

        $jsonPath = __DIR__ . DIRECTORY_SEPARATOR
            . 'element' . DIRECTORY_SEPARATOR . 'alcamo.json';

        $jsPath = __DIR__ . DIRECTORY_SEPARATOR
            . 'element' . DIRECTORY_SEPARATOR . 'alcamo.js';

        $mCssGz = gmdate('YmdHis', filemtime("$cssPath.gz"));

        $mJson = gmdate('YmdHis', filemtime($jsonPath));

        $mJsGz = gmdate('YmdHis', filemtime("$jsPath.gz"));

        return [
            'simple' => [
                [ 'dc:title' => 'Foo | Bar' ],
                [ 'htdocsDir' => __DIR__, 'htdocsUrl' => '/' ],
                null,
                null,
                '<!DOCTYPE html>'
                . '<html xmlns="http://www.w3.org/1999/xhtml">'
                . '<head>'
                . '<title property="dc:title">Foo | Bar</title>'
                . '</head>'
                . '<body>Lorem ipsum.</body>'
                . '<!-- Served in 0.123456s -->'
                . '</html>'
            ],
            'with-metadata-and-resources' => [
                [
                    'dc:identifier' => 'baz.qux',
                    'dc:language' => 'en-UG'
                ],
                [ 'htdocsDir' => __DIR__, 'htdocsUrl' => '/' ],
                [
                    $cssPath,
                    $jsPath,
                    [ $jsonPath, 'manifest' ]
                ],
                new Nodes(new Comment('consetetur sadipscing elitr')),
                '<!DOCTYPE html>'
                . '<html xmlns="http://www.w3.org/1999/xhtml" id="baz.qux" lang="en-UG">'
                . '<head>'
                . '<meta property="dc:identifier" content="baz.qux"/>'
                . '<meta property="dc:language" content="en-UG"/>'
                . "<link rel=\"stylesheet\" href=\"/element/alcamo.css.gz?m=$mCssGz\"/>"
                . "<script src=\"/element/alcamo.js.gz?m=$mJsGz\"></script>"
                . "<link rel=\"manifest\" href=\"/element/alcamo.json?m=$mJson\" type=\"application/json\"/>"
                . '<!-- consetetur sadipscing elitr -->'
                . '</head>'
                . '<body>Lorem ipsum.</body>'
                . '<!-- Served in 0.123456s -->'
                . '</html>'
            ]
        ];
    }
}
