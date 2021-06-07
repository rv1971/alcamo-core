<?php

namespace alcamo\exception;

/**
 * @brief Exception thrown by an attempt to modify a locked object.
 *
 * @date Last reviewed 2021-06-07
 */
class Locked extends AbstractObjectStateException
{
    /** @copydoc AbstractObjectStateException::MESSAGE_INCIPIT */
    public const MESSAGE_INCIPIT = 'Attempt to modify locked';
}
