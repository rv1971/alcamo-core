<?php

namespace alcamo\dom;

/// Text class for use in DOMDocument::registerNodeClass().
class Text extends \DOMText
{
    public function __toString()
    {
        return $this->wholeText;
    }
}
