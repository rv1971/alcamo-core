<?php

namespace alcamo\html_creation;

use alcamo\modular_class\ModuleTrait;
use alcamo\html_creation\element\{Body, Head, Html};
use alcamo\xml_creation\{Comment, DoctypeDecl, Nodes};

class PageFactory
{
    use ModuleTrait {
        init as moduleInit;
    }

    public const NAME = 'page';

    private $created_; ///< Microtime of creation of this object.
    private $resourceFactory_;

    public function __construct(?ResourceFactory $resourceFactory = null)
    {
        $this->created_ = microtime(true);
        $this->resourceFactory_ = $resourceFactory;
    }

    public function getResourceFactory(): ?ResourceFactory
    {
        return $this->resourceFactory_;
    }

    public function init(Factory $factory)
    {
        $this->moduleInit($factory);

        if (!isset($this->resourceFactory_)) {
            $this->resourceFactory_ =
                new ResourceFactory($this->getUrlFactory());
        }
    }

    /// Return seconds elapsed since creation.
    public function elapsed(): float
    {
        return microtime(true) - $this->created_;
    }

    public function createDoctypeDecl($intSubset = null): DoctypeDecl
    {
        return new DoctypeDecl('html', null, $intSubset);
    }

    public function createDefaultHtmlAttrs(): array
    {
        $attrs = [ 'xmlns' => 'http://www.w3.org/1999/xhtml' ];

        foreach ($this->getRdfaData()->getPrefixBindings() as $prefix => $ns) {
            $attrs["xmlns:$prefix"] = $ns;
        }

        if (isset($this->getRdfaData()['dc:identifier'])) {
            $attrs['id'] = $this->getRdfaData()['dc:identifier'];
        }

        if (isset($this->getRdfaData()['dc:language'])) {
            $attrs['lang'] = $this->getRdfaData()['dc:language'];
        }

        return $attrs;
    }

    public function createHtmlOpen(?array $attrs = null): string
    {
        return
            (new Html(null, (array)$attrs + $this->createDefaultHtmlAttrs()))
            ->createOpeningTag();
    }

    public function createDefaultHeadAttrs(): array
    {
        return [];
    }

    public function createHead(
        ?array $resources = null,
        ?Nodes $extraNodes = null,
        ?array $attrs = null
    ): Head {
        $content = [ $this->getRdfaData()->toHtmlNodes() ];

        if (isset($resources)) {
            $content[] =
                $this->resourceFactory_->createElementsFromItems($resources);
        }

        if (isset($extraNodes)) {
            $content[] = $extraNodes;
        }

        return
            new Head($content, (array)$attrs + $this->createDefaultHeadAttrs());
    }

    public function createDefaultBodyAttrs(): array
    {
        return [];
    }

    public function createBodyOpen(?array $attrs = null): string
    {
        return
            (new Body(null, (array)$attrs + $this->createDefaultBodyAttrs()))
            ->createOpeningTag();
    }

    public function createBegin(
        ?array $resources = null,
        ?Nodes $extraHeadNodes = null
    ): string {
        return $this->createDoctypeDecl()
            . $this->createHtmlOpen()
            . $this->createHead($resources, $extraHeadNodes)
            . $this->createBodyOpen();
    }

    public function createEnd(): string
    {
        return
            (new Body())->createClosingTag()
            . (new Comment(sprintf("Served in %.6fs", $this->elapsed())))
            . (new Html())->createClosingTag();
    }
}
