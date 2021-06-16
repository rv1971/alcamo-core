<?php

namespace alcamo\html_creation\element;

/**
 * @brief HTML element \<optgroup>
 *
 * @date Last reviewed 2021-06-16
 */
class Optgroup extends AbstractOptionList
{
    public const TAG_NAME = "optgroup";

    /**
     * @brief Create from sequence of values
     *
     * @param @label Group label
     *
     * @copydetails AbstractOptionList::createOptionArrayFromSequence()
     *
     * @param @attrs Further attributes.
     */
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

    /**
     * @brief Create from map of values to contents
     *
     * @param @label Group label
     *
     * @copydetails AbstractOptionList::createOptionArrayFromMap()
     *
     * @param @attrs Further attributes.
     */
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
