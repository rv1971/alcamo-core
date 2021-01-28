<?php

namespace alcamo\html_creation\element;

use alcamo\html_creation\Element;

/// Base class for HTML element classes for specific tag names
abstract class AbstractSpecificElement extends Element
{
    public function __construct($content = null, ?iterable $attrs = null)
    {
        parent::__construct(static::TAG_NAME, $attrs, $content);
    }
}
