<?php

namespace alcamo\rdfa;

use alcamo\collection\ReadonlyCollection;
use alcamo\xml_creation\Nodes;

/**
 * @brief Collection of RDFa statements
 *
 * Readonly map of properties to objects.
 *
 * @invariant Immutable class.
 *
 * @date Last reviewed 2021-06-23
 */
class RdfaData extends ReadonlyCollection
{
    /**
     * @brief Create from map of property CURIEs to objects
     *
     * @param $data Map of property CURIEs to objects. @copydetail
     * alcamo::object_creation::createArray()
     *
     * @param $factory RDFa factory used to create RDFa data by calling
     * Factory::createArray(). Defaults to a new instance of Factory.
     */
    public static function newFromIterable(
        iterable $data,
        ?Factory $factory = null
    ) {
        if (!isset($factory)) {
            $factory = new Factory();
        }

        return new self($factory->createArray($data));
    }

    private function __construct(array $data)
    {
        parent::__construct($data);

        /** Add `meta:charset` from dc:format if appropriate. */
        if (
            !isset($this->data_['meta:charset'])
            && isset($this->data_['dc:format'])
        ) {
            $charset =
                $this->data_['dc:format']->getObject()->getParams()['charset']
                ?? null;

            if (isset($charset)) {
                $this->data_['meta:charset'] = new MetaCharset($charset);
            }
        }
    }

    /// Key-sorted map of all used CURIE prefixes to their bindings
    public function getPrefixMap(): array
    {
        $map = [];

        foreach ($this->data_ as $key => $value) {
            $map += is_array($value)
                ? reset($value)->getPrefixMap()
                : $value->getPrefixMap();
        }

        ksort($map);

        return $map;
    }

    /// Create HTML nodes for the document head
    public function toHtmlNodes(): ?Nodes
    {
        $result = [];

        /** If `meta:charset` is present, output it first. */
        if (isset($this->data_['meta:charset'])) {
            $result[] = $this->data_['meta:charset']->toHtmlNodes();
        }

        foreach ($this->data_ as $key => $value) {
            if ($key == 'meta:charset') {
                continue;
            }

            if (is_array($value)) {
                foreach ($value as $item) {
                    $result[] = $item->toHtmlNodes();
                }
            } else {
                $result[] = $value->toHtmlNodes();
            }
        }

        return new Nodes($result);
    }

    /**
     * @warning The implementation does not support:
     * - RDFa data with multiple values for one property that generates HTTP
     *   headers.
     * - Multiple RDFa properties which generate the same header.
     */
    public function toHttpHeaders(): ?array
    {
        $result = [];

        foreach ($this->data_ as $stmt) {
            if (!is_array($stmt)) {
                $result += (array)$stmt->toHttpHeaders();
            }
        }

        return $result;
    }

    public function alterSession()
    {
        foreach ($this->data_ as $value) {
            if (method_exists($value, 'alterSession')) {
                $value->alterSession();
            }
        }
    }

    /// Return new object, adding properties without overwriting existing ones
    public function add(self $rdfaData): self
    {
        $newData = $this->data_;

        foreach ($rdfaData->data_ as $key => $value) {
            if (isset($newData[$key])) {
                /** If a key is already present, add new data to its
                 *  values. In all cases, the result is an array indexed by
                 *  the string representations of the values. */
                $data = $newData[$key];

                if (!is_array($data)) {
                    $newData[$key] = [ (string)$data => $data ];
                }

                if (is_array($value)) {
                    $newData[$key] += $value;
                } else {
                    $newData[$key] += [ (string)$value => $value ];
                }
            } else {
                $newData[$key] = $value;
            }
        }

        return new self($newData);
    }

    /// Return a new object with added properties, overwriting existing ones
    public function replace(self $rdfaData): self
    {
        return new self($rdfaData->data_ + $this->data_);
    }
}
