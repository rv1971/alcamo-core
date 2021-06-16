<?php

namespace alcamo\html_creation\element;

/**
 * @brief HTML element \<option>
 *
 * @date Last reviewed 2021-06-16
 */
class Option extends AbstractSpecificElement
{
    public const TAG_NAME = "option";

    /**
     * @param $value `value` attribute.
     *
     * @param $content `content` attribute. Defaults to $value.
     *
     * @param $compareTo Set the attribute `checked` if $value is is contained
     *  in $compareTo (see below). This is checked as follows:
     * - Call `$compareTo->contains($value)` if this method is available.
     * - Else, if $compareTo is an array, check whether is contains $value.
     * - Else, check whether $value is equal to $compareTo using the ==
     *   operator.
     *
     * @param $attrs Further attributes. $content and $name override
     * `$attrs['content']` and `$attrs['name']`.
     */
    public function __construct(
        $value,
        $content = null,
        $compareTo = null,
        ?array $attrs = null
    ) {
      /* If $content is unset, $value will be used as content, and in this
       * case the `value` attribute is redundant. */
        $attrs = isset($content)
            ? compact('value') + (array)$attrs
            : (array)$attrs;

        if (isset($compareTo)) {
            switch (true) {
                case is_callable([ $compareTo, 'contains' ]):
                    $attrs['selected'] = $compareTo->contains($value);
                    break;

                case is_array($compareTo):
                    $attrs['selected'] = in_array($value, $compareTo, true);
                    break;

                default:
                    $attrs['selected'] = $value == $compareTo;
            }
        }

        parent::__construct($content ?? $value, $attrs);
    }
}
