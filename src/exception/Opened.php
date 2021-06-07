<?php

namespace alcamo\exception;

/**
 * @brief Exception thrown by an attempt to open an already opened object.
 *
 * @date Last reviewed 2021-06-07
 */
class Opened extends AbstractObjectStateException
{
    /** @copydoc AbstractObjectStateException::MESSAGE_INCIPIT */
    public const MESSAGE_INCIPIT = 'Attempt to open already opened';
}
