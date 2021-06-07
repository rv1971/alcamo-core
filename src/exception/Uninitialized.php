<?php

namespace alcamo\exception;

/**
 * @brief Exception thrown by an attempt to access an uninitialized object.
 *
 * @date Last reviewed 2021-06-07
 */
class Uninitialized extends AbstractObjectStateException
{
    /** @copydoc AbstractObjectStateException::MESSAGE_INCIPIT */
    public const MESSAGE_INCIPIT = 'Attempt to access uninitialized';
}
