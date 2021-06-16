<?php

namespace alcamo\html_creation\element;

/**
 * @brief HTML element \<input> of type `hidden`
 *
 * @date Last reviewed 2021-06-16
 */
class Hidden extends Input
{
    public const TYPE = 'hidden';

    /**
     * @param $name `name` attribute.
     *
     * @param $value `value` attribute.
     *
     * @param $attrs Further attributes. If `$attrs['type']` is not set and
     * a class constant TYPE is defiend, `$attrs['type']` is set to
     * static::TYPE. $name and $value override `$attrs['name']` and
     * `$attrs['value']`.
     */
    public function __construct(string $name, $value, ?array $attrs = null)
    {
        parent::__construct(compact('name', 'value') + (array)$attrs);
    }
}
