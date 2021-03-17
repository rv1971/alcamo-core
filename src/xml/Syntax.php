<?php

namespace alcamo\xml;

class Syntax
{
    // fragements for use in regular expressions

    public const NAME_START_CHAR = '[\pL:_]';

    public const NAME_CHAR =
        '[-\pL:.\d\x{B7}\x{0300}-\x{036F}\x{203F}-\x{2040}]';

    public const NAME = self::NAME_START_CHAR . self::NAME_CHAR . '*';

    public const NMTOKEN = self::NAME_CHAR . '+';

    public const NC_NAME_START_CHAR = '[\pL_]';

    public const NC_NAME_CHAR =
        '[-\pL.\d\x{B7}\x{0300}-\x{036F}\x{203F}-\x{2040}]';

    public const NC_NAME = self::NC_NAME_START_CHAR . self::NC_NAME_CHAR . '*';

    public const Q_NAME = '(' . self::NC_NAME . ':)?' . self::NC_NAME;

    // complete regular expressions

    public const NAME_REGEXP    = '/^' . self::NAME    . '$/u';

    public const NMTOKEN_REGEXP = '/^' . self::NMTOKEN . '$/u';

    public const NC_NAME_REGEXP = '/^' . self::NC_NAME . '$/u';

    public const Q_NAME_REGEXP  = '/^' . self::Q_NAME  . '$/u';
}
