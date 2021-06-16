<?php

namespace alcamo\html_creation\element;

/**
 * @brief HTML element \<input> of type `checkbox`
 *
 * @date Last reviewed 2021-06-16
 */
class Checkbox extends Input
{
    public const TYPE = 'checkbox';

    /**
     * @param $name `name` attribute.
     *
     * @param $value `value` attribute.
     *
     * @param $compareTo Set the attribute `checked` if $value is is contained
     *  in $compareTo (see below). This is checked as follows:
     * - Call `$compareTo->contains($value)` if this method is available.
     * - Else, if $compareTo is an array, check whether is contains $value.
     * - Else, check whether $value is equal to $compareTo using the ==
     *   operator.
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
            switch (true) {
                case is_callable([ $compareTo, 'contains' ]):
                    $attrs['checked'] = $compareTo->contains($value);
                    break;

                case is_array($compareTo):
                    $attrs['checked'] = in_array($value, $compareTo, true);
                    break;

                default:
                    $attrs['checked'] = $value == $compareTo;
            }
        }

        parent::__construct($attrs);
    }
}
