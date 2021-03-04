<?php

namespace alcamo\html_creation;

use alcamo\html_creation\element\{
    AbstractSpecificElement,
    Icon,
    Link,
    Script,
    Stylesheet
};
use alcamo\iana\MediaType;
use alcamo\url_creation\{HasUrlFactoryTrait, UrlFactoryInterface};
use alcamo\xml_creation\Nodes;

class ResourceFactory
{
    use HasUrlFactoryTrait;

    public function __construct(UrlFactoryInterface $urlFactory)
    {
        $this->urlFactory_ = $urlFactory;
    }

    public function createElementFromPath(
        string $path,
        ?array $attrs = null
    ): Element {
      /** Determine media type from filename unless the type is set in
       *  `$attrs`. */
        $type = isset($attrs['type'])
        ? ($attrs['type'] instanceof MediaType
         ? $attrs['type']
         : MediaType::newFromString($attrs['type']))
        : MediaType::newFromFilename($path);

        $url = $this->urlFactory_->createFromPath($path);

        switch ($type->getType()) {
            case 'image':
                /** Return Icon if `$path` is an image file. */
                return Icon::newFromLocalUrl(
                    $url,
                    compact('type') + (array)$attrs,
                    $path
                );
        }

        switch ($type->getTypeAndSubtype()) {
          /** Return Script if `$path` is a JavaScript file. */
            case 'application/javascript':
                return Script::newFromLocalUrl($url, $attrs, $path);

          /** Return Stylesheet if `$path` is a CSS file. */
            case 'text/css':
                return Stylesheet::newFromLocalUrl($url, $attrs, $path);

          /** In all other cases, return a Link. `$attrs['rel']` must be set. */
            default:
                return Link::newFromRelAndLocalUrl(
                    $attrs['rel'],
                    $url,
                    $attrs,
                    $path
                );
        }
    }

    public function createElementsFromItems(iterable $items): Nodes
    {
        $nodes = [];

        foreach ($items as $item) {
            switch (true) {
                /** - If an item is an HTML element, use it as-is. */
                case $item instanceof AbstractSpecificElement:
                    $nodes[] = $item;
                    break;

                /** - If an item is an array, then take the first element as
                 *    the path. If the second element is an array, take it as
                 *    an array of attributes, otherwise as the value for the
                 *    `rel` attribute. */
                case is_array($item):
                    $nodes[] = $this->createElementFromPath(
                        $item[0],
                        is_array($item[1]) ? $item[1] : [ 'rel' => $item[1] ]
                    );

                    break;

                /** In all other cases, take the item as the path. */
                default:
                    $nodes[] = $this->createElementFromPath($item);
            }
        }

        return new Nodes($nodes);
    }
}
