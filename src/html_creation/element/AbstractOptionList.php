<?php

namespace alcamo\html_creation\element;

use alcamo\xml_creation\Raw;

/**
 * @brief Common base class for Optgroup and Select
 *
 * @date Last reviewed 2021-06-16
 */
abstract class AbstractOptionList extends AbstractSpecificElement
{
    /**
     * @brief Create array of options from sequence of values
     *
     * @param $compareTo See $compareTo parameter in Option::__construct().
     *
     * Creates an Option element for each value unless it is an element allowed
     * within \<select>.
     */
    public static function createOptionArrayFromSequence(
        iterable $values,
        $compareTo = null
    ) {
        $options = [];

        foreach ($values as $value) {
            $options[] =
                ($value instanceof Raw
                 || $value instanceof Option
                 || $value instanceof Optgroup
                 || $value instanceof AbstractScriptSupportingElement)
                ? $value
                : new Option($value, null, $compareTo);
        }

        return $options;
    }

    /**
     * @brief Create array of options from map of values to contents
     *
     * @param $compareTo See $compareTo parameter in Option::__construct().
     *
     * Creates an Option element for each value unless it is an element allowed
     * within \<select>.
     */
    public static function createOptionArrayFromMap(
        iterable $map,
        $compareTo = null
    ) {
        $options = [];

        foreach ($map as $value => $optionContent) {
            $options[] =
                ($optionContent instanceof Raw
                 || $optionContent instanceof Option
                 || $optionContent instanceof Optgroup
                 || $optionContent instanceof AbstractScriptSupportingElement)
                ? $optionContent
                : new Option($value, $optionContent, $compareTo);
        }

        return $options;
    }
}
