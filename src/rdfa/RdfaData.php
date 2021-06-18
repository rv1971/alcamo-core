<?php

namespace alcamo\rdfa;

use alcamo\collection\ReadonlyCollection;
use alcamo\xml_creation\Nodes;

class RdfaData extends ReadonlyCollection
{
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

    /// Add further properties without overwriting existing ones.
    public function add(self $rdfaData): self
    {
        foreach ($rdfaData->data_ as $key => $value) {
            if (isset($this->data_[$key])) {
                /** If a key is already present, add new data to its
                 *  values. In all cases, the result is an array indexed by
                 *  the string representations of the values. */

                $data = $this->data_[$key];

                if (!is_array($data)) {
                    $this->data_[$key] = [ (string)$data => $data ];
                }

                if (is_array($value)) {
                    $this->data_[$key] += $value;
                } else {
                    $this->data_[$key] += [ (string)$value => $value ];
                }
            } else {
                $this->data_[$key] = $value;
            }
        }

        return $this;
    }

  /// Add further properties, overwriting existing ones.
    public function replace(self $rdfaData): self
    {
        $this->data_ = $rdfaData->data_ + $this->data_;

        return $this;
    }
}
