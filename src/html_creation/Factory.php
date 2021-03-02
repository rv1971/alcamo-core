<?php

namespace alcamo\html_creation;

use alcamo\conf\HasConfTrait;
use alcamo\modular_class\ParentTrait;
use alcamo\rdfa\{HasRdfaDataTrait, RdfaData};
use alcamo\url_creation\{
    DirMapUrlFactory,
    HasUrlFactoryTrait,
    UrlFactoryInterface
};

class Factory implements \Countable, \Iterator, \ArrayAccess
{
    use HasConfTrait;
    use HasRdfaDataTrait;
    use HasUrlFactoryTrait;
    use ParentTrait;

    public const DEFAULT_RDFA_DATA = [
        'dc:format' => 'application/xhtml+xml; charset="UTF-8"'
    ];

    public static function newFromRdfaData(
        iterable $rdfaData,
        ?array $conf = null,
        ?array $modules = null,
        ?UrlFactoryInterface $urlFactory = null
    ) {
        return new static(
            RdfaData::newfromIterable($rdfaData),
            $conf,
            $modules,
            $urlFactory
        );
    }

    public function __construct(
        RdfaData $rdfaData,
        ?array $conf = null,
        ?array $modules = null,
        ?UrlFactoryInterface $urlFactory = null
    ) {
        $this->rdfaData_ = RdfaData::newFromIterable(static::DEFAULT_RDFA_DATA)
            ->replace($rdfaData);

        $this->conf_ = (array)$conf;

        $this->urlFactory_ =
            $urlFactory ?? DirMapUrlFactory::newFromConf($this->conf_);

        $this->addModules((array)$modules);

        if (!isset($this['page'])) {
            $this->addModule(new PageFactory());
        }
    }
}
