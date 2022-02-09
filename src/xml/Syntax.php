<?php

namespace alcamo\xml;

/**
 * @brief Regular expressions for XML syntax productions
 *
 * @sa [Extensible Markup Language (XML)](https://www.w3.org/TR/xml)
 *
 * @sa [Namespaces in XML 1.0](https://www.w3.org/TR/xml-names/)
 *
 * @date Last reviewed 2021-06-15
 */
class Syntax
{
    /// [NameStartChar](https://www.w3.org/TR/xml/#NT-NameStartChar) fragment
    public const NAME_START_CHAR = '[\pL:_]';

    /// [NameChar](https://www.w3.org/TR/xml/#NT-NameChar) fragment
    public const NAME_CHAR =
        '[-\pL:_.\d\x{B7}\x{0300}-\x{036F}\x{203F}-\x{2040}]';

    /// [Name](https://www.w3.org/TR/xml/#NT-Name) fragment
    public const NAME = self::NAME_START_CHAR . self::NAME_CHAR . '*';

    /// [Nmtoken](https://www.w3.org/TR/xml/#NT-Nmtoken) fragment
    public const NMTOKEN = self::NAME_CHAR . '+';

    /// [NCNameStartChar](https://www.w3.org/TR/xml-names/#NT-NCNameStartChar) fragment
    public const NC_NAME_START_CHAR = '[\pL_]';

    /// [NCNameChar](https://www.w3.org/TR/xml-names/#NT-NCNameChar) fragment
    public const NC_NAME_CHAR =
        '[-\pL_.\d\x{B7}\x{0300}-\x{036F}\x{203F}-\x{2040}]';

    /// [NCName](https://www.w3.org/TR/xml-names/#NT-NCName) fragment
    public const NC_NAME = self::NC_NAME_START_CHAR . self::NC_NAME_CHAR . '*';

    /// [QName](https://www.w3.org/TR/xml-names/#NT-QName) fragment
    public const Q_NAME = '(' . self::NC_NAME . ':)?' . self::NC_NAME;

    /// [NameChar](https://www.w3.org/TR/xml/#NT-NameChar) regexp
    public const NAME_REGEXP    = '/^' . self::NAME    . '$/u';

    /// [Nmtoken](https://www.w3.org/TR/xml/#NT-Nmtoken) regexp
    public const NMTOKEN_REGEXP = '/^' . self::NMTOKEN . '$/u';

    /// [NCName](https://www.w3.org/TR/xml-names/#NT-NCName) regexp
    public const NC_NAME_REGEXP = '/^' . self::NC_NAME . '$/u';

    /// [QName](https://www.w3.org/TR/xml-names/#NT-QName) regexp
    public const Q_NAME_REGEXP  = '/^' . self::Q_NAME  . '$/u';
}
