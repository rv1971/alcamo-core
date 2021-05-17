<?php

namespace alcamo\exception;

class Opened extends AbstractObjectStateException
{
    public const MESSAGE_INCIPIT = 'Attempt to open already opened';
}
