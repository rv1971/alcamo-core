<?php

namespace alcamo\exception;

/**
 * @brief Exception thrown by an attempt to use an already closed object.
 *
 * @date Last reviewed 2021-06-07
 */
class Closed extends AbstractObjectStateException
{
    /** @copydoc AbstractObjectStateException::MESSAGE_INCIPIT */
    public const MESSAGE_INCIPIT = 'Attempt to use closed';
}
