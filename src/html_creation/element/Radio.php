<?php

namespace alcamo\html_creation\element;

/**
 * @brief HTML element \<input> of type `radio`
 *
 * @date Last reviewed 2021-06-16
 */
class Radio extends Input
{
    public const TYPE = 'radio';

    /**
     * @param $name `name` attribute.
     *
     * @param $value `value` attribute.
     *
     * @param $compareTo If $value is identical to $compareTo (using the ===
     * operator), set the attribute `checked`.
     *
     * @param $attrs Further attributes. If `$attrs['type']` is not set and
     * a class constant TYPE is defiend, `$attrs['type']` is set to
     * static::TYPE. $name and $value override `$attrs['name']` and
     * `$attrs['value']`.
     */
    public function __construct(
        $name,
        $value,
        $compareTo = null,
        ?array $attrs = null
    ) {
        $attrs = compact('name', 'value') + (array)$attrs;

        if (isset($compareTo)) {
            $attrs['checked'] = $value == $compareTo;
        }

        parent::__construct($attrs);
    }
}
