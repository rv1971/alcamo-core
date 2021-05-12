<?php

namespace alcamo\html_creation\element;

use alcamo\exception\InvalidEnumerator;

class Input extends AbstractSpecificElement
{
    public const TAG_NAME = "input";

    public const TYPES = [
        "button",
        "checkbox",
        "color",
        "date",
        "datetime-local",
        "email",
        "file",
        "hidden",
        "image",
        "month",
        "number",
        "password",
        "radio",
        "range",
        "reset",
        "search",
        "submit",
        "tel",
        "text",
        "time",
        "url",
        "week"
    ];

    public function __construct(string $type, array $attrs)
    {
        if (!in_array($type, static::TYPES)) {
            throw new InvalidEnumerator(
                $type,
                static::TYPES,
                '; not a valid <input> type'
            );
        }

        parent::__construct(null, compact('type') + $attrs);
    }
}
