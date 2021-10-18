<?php

/**
 * @namespace alcamo::xml::exception
 *
 * @brief XML-specific exception classes
 */

namespace alcamo\xml\exception;

use alcamo\exception\{ExceptionInterface, ExceptionTrait};

/**
 * @brief Exception thrown when encountering an unknown namespace prefix
 */
class UnknownNamespacePrefix extends \UnexpectedValueException implements
    ExceptionInterface
{
    use ExceptionTrait;

    public const NORMALIZED_MESSAGE = 'Unknown namespace prefix {prefix}';
}
