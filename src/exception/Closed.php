<?php

namespace alcamo\exception;

class Closed extends AbstractObjectStateException
{
    public const MESSAGE_INCIPIT = 'Attempt to use closed';
}
