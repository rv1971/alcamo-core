<?php

namespace alcamo\html_creation\element;

/// Common base class for Optgroup and Select
abstract class AbstractOptionList extends AbstractSpecificElement
{
  /// Create array of options from sequence of values
    public static function createOptionArrayFromSequence(
        iterable $values,
        $compareTo = null
    ) {
        $options = [];

        foreach ($values as $value) {
            $options[] = $value instanceof Option
            ? $value
            : new Option($value, null, $compareTo);
        }

        return $options;
    }

  /// Create from map of values to contents
    public static function createOptionArrayFromMap(
        iterable $values,
        $compareTo = null
    ) {
        $options = [];

        foreach ($values as $value => $optionContent) {
            $options[] = $optionContent instanceof Option
            ? $optionContent
            : new Option($value, $optionContent, $compareTo);
        }

        return $options;
    }
}
