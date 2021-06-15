<?php

namespace alcamo\html_creation\element;

use alcamo\html_creation\Element;

/**
 * @brief Base class for HTML element classes for specific tag names
 *
 * @attention Derived classes must define a public constant TAG_NAME
 * containing the tag name.
 *
 * @date Last reviewed 2021-06-15
 */
abstract class AbstractSpecificElement extends Element
{
    public function __construct($content = null, ?iterable $attrs = null)
    {
        parent::__construct(static::TAG_NAME, $attrs, $content);
    }
}
