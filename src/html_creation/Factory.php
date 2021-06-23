<?php

namespace alcamo\html_creation;

use SebastianBergmann\Exporter\Exporter;
use alcamo\exception\FileLocation;
use alcamo\html_creation\element\{B, P, Ul};
use alcamo\modular_class\ParentTrait;
use alcamo\rdfa\{HasRdfaDataTrait, RdfaData};
use alcamo\url_creation\{
    HasUrlFactoryTrait,
    TrivialUrlFactory,
    UrlFactoryInterface
};
use alcamo\xml_creation\Nodes;

class Factory implements \Countable, \Iterator, \ArrayAccess
{
    use HasRdfaDataTrait;
    use HasUrlFactoryTrait;
    use ParentTrait;

    /// Create XHTML by default
    public const DEFAULT_RDFA_DATA = [
        'dc:format' => 'application/xhtml+xml; charset="UTF-8"'
    ];

    public static function newFromRdfaData(
        iterable $rdfaData,
        ?array $modules = null,
        ?UrlFactoryInterface $urlFactory = null
    ) {
        return new static(
            RdfaData::newfromIterable($rdfaData),
            $modules,
            $urlFactory
        );
    }

    public function __construct(
        ?RdfaData $rdfaData = null,
        ?array $modules = null,
        ?UrlFactoryInterface $urlFactory = null
    ) {
        $this->rdfaData_ = RdfaData::newFromIterable(static::DEFAULT_RDFA_DATA);

        if (isset($rdfaData)) {
            $this->rdfaData_ = $this->rdfaData_->replace($rdfaData);
        }

        $this->urlFactory_ = $urlFactory ?? new TrivialUrlFactory();

        $this->addModules((array)$modules);

        if (!isset($this['page'])) {
            $this->addModule(new PageFactory());
        }
    }

    public function renderThrowable(\Throwable $e): Nodes
    {
        $exporter = new Exporter();

        $codeLocation = FileLocation::newFromThrowable($e);

        $result = [ new P([ new B(get_class($e)), " at $codeLocation" ]) ];

        $result[] = new P(new B($e->getMessage()));

        $props = [];

        foreach (get_object_vars($e) as $key => $value) {
            $props[] = "$key = " . $exporter->export($value);
        }

        if ($props) {
            $result[] = new Ul($props);
        }

        foreach ($e->getTrace() as $item) {
            $itemHtml = [ "{$item['function']}()" ];

            if (isset($item['file'])) {
                $itemHtml[] = ' in ' . FileLocation::newFromBacktraceItem($item);
            }

            $result[] = new P($itemHtml);
        }

        return new Nodes($result);
    }
}
