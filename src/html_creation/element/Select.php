<?php

namespace alcamo\html_creation\element;

class Select extends AbstractOptionList
{
    public const TAG_NAME = "select";

    /**
     * @brief Create from sequence of values
     *
     * @param @name Value for `name` attribute
     *
     * @copydetails AbstractOptionList::createOptionArrayFromSequence()
     *
     * @param @attrs Further attributes.
     */
    public static function newFromValueSequence(
        $name,
        iterable $values,
        $compareTo = null,
        ?array $attrs = null
    ) {
        return new self(
            $name,
            self::createOptionArrayFromSequence($values, $compareTo),
            $attrs
        );
    }

    /**
     * @brief Create from map of values to contents
     *
     * @param @name Value for `name` attribute
     *
     * @copydetails AbstractOptionList::createOptionArrayFromMap()
     *
     * @param @attrs Further attributes.
     */
    public static function newFromMap(
        $name,
        iterable $values,
        $compareTo = null,
        ?array $attrs = null
    ) {
        return new self(
            $name,
            self::createOptionArrayFromMap($values, $compareTo),
            $attrs
        );
    }

    /**
     * If $name ends with `[]`, the attribute `multiple` is automatically set.
     */
    public function __construct($name, $content, ?array $attrs = null)
    {
        $attrs = compact('name') + (array)$attrs;

        if (isset($name) && substr($name, -2) == '[]') {
            $attrs['multiple'] = true;
        }

        parent::__construct($content, $attrs);
    }
}
