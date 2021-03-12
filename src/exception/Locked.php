<?php

namespace alcamo\exception;

class Locked extends AbstractObjectStateException
{
    public const MESSAGE_INCIPIT = 'Attempt to modify locked';
}
