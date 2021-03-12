<?php

namespace alcamo\exception;

class Uninitialized extends AbstractObjectStateException
{
    public const MESSAGE_INCIPIT = 'Attempt to access uninitialized';
}
