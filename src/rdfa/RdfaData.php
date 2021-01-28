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
            $this->data_['dc:format']->getObject()->getParams()['charset'] ?? null;

            if (isset($charset)) {
                $this->data_['meta:charset'] = new MetaCharset($charset);
            }
        }
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

    public function toHttpHeaders(): ?array
    {
        $result = [];

        foreach ($this->data_ as $value) {
            if (is_array($value)) {
                foreach ($value as $item) {
                    $result += (array)$item->toHttpHeaders();
                }
            } else {
                $result += (array)$value->toHttpHeaders();
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
    public function add(self $rdfaData)
    {
        foreach ($rdfaData->data_ as $key => $value) {
            if (isset($this->data_[$key])) {
                /** If a key is already present, add new data to its values */
                if (is_array($this->data_[$key])) {
                    $this->data_[$key][] = $value;
                } else {
                    $this->data_[$key] = [ $this->data_[$key], $value ];
                }
            } else {
                $this->data_[$key] = $value;
            }
        }
    }

  /// Add further properties, overwriting existing ones.
    public function replace(self $rdfaData)
    {
        $this->data_ = $rdfaData->data_ + $this->data_;
    }
}
