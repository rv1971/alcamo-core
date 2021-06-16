<?php

namespace alcamo\html_creation\element;

use alcamo\xml_creation\Raw;

/**
 * @brief HTML element \<table>
 *
 * @date Last reviewed 2021-06-16
 */
class Table extends AbstractRowgroupElement
{
    public const TAG_NAME = "table";

    /**
     * @param $thead Thead or Tr or iterable of *cells for one header row* or
     * `null`.
     *
     * @param $tbody Tbody or Tr or iterable of *rows* or `null`.
     *
     * @param $tfoot Tfoot or Tr or iterable of *cells for one header row* or
     * `null`.
     *
     * @param $attrs iterable|null Attributes for the `\<table>` element.
     */
    public static function newFromRowgroups(
        $thead = null,
        $tbody = null,
        $tfoot = null,
        ?iterable $attrs = null
    ) {
        $content = [];

        switch (true) {
            case !isset($thead):
                break;

            case $thead instanceof Raw:
            case $thead instanceof Thead:
                $content[] = $thead;
                break;

            case $thead instanceof Tr:
                $content[] = new Thead($thead);
                break;

            default:
                $content[] = Thead::newFromCellsIterable($thead);
        }

        switch (true) {
            case !isset($tbody):
                break;

            case $thead instanceof Raw:
            case $tbody instanceof Tbody:
                $content[] = $tbody;
                break;

            case $tbody instanceof Tr:
                $content[] = new Tbody($tbody);
                break;

            default:
                $content[] = Tbody::newFromRowsIterable($tbody);
        }

        switch (true) {
            case !isset($tfoot):
                break;

            case $thead instanceof Raw:
            case $tfoot instanceof Tfoot:
                $content[] = $tfoot;
                break;

            case $tfoot instanceof Tr:
                $content[] = new Tfoot($tfoot);
                break;

            default:
                $content[] = Tfoot::newFromCellsIterable($tfoot);
        }

        return new self($content, $attrs);
    }
}
