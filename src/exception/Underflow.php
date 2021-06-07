<?php

namespace alcamo\exception;

/**
 * @brief Exception thrown when an underflow occurs in an object.
 *
 * @date Last reviewed 2021-06-07
 */
class Underflow extends AbstractObjectStateException
{
    /** @copydoc AbstractObjectStateException::MESSAGE_INCIPIT */
    public const MESSAGE_INCIPIT = 'Underflow in';
}
