<?php

namespace alcamo\html_creation\element;

class Optgroup extends AbstractOptionList
{
    const TAG_NAME = "optgroup";

  /// Create from sequence of values
    public static function newFromValueSequence(
        $label,
        iterable $values,
        $compareTo = null,
        ?array $attrs = null
    ) {
        return new self(
            $label,
            self::createOptionArrayFromSequence($values, $compareTo),
            $attrs
        );
    }

  /// Create from map of values to contents
    public static function newFromMap(
        $label,
        iterable $values,
        $compareTo = null,
        ?array $attrs = null
    ) {
        return new self(
            $label,
            self::createOptionArrayFromMap($values, $compareTo),
            $attrs
        );
    }

    public function __construct($label, $content, ?array $attrs = null)
    {
        parent::__construct($content, compact('label') + (array)$attrs);
    }
}
