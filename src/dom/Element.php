<?php

namespace alcamo\dom;

/// Element class for use in DOMDocument::registerNodeClass().
class Element extends \DOMElement implements \IteratorAggregate
{
    use HasXNameTrait;

    public function __toString()
    {
        return $this->textContent;
    }

    public function getIterator()
    {
        return new ChildElementsIterator($this);
    }

    /// Run XPath query with this node as context node.
    public function query(string $expr)
    {
        return $this->ownerDocument->xPath()->query($expr, $this);
    }

    /// Run and evaluate XPath query with this node as context node.
    public function evaluate(string $expr)
    {
        return $this->ownerDocument->xPath()->evaluate($expr, $this);
    }
}
