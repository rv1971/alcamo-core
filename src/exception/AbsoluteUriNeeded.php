<?php

namespace alcamo\exception;

class AbsoluteUriNeeded extends \UnexpectedValueException
{
    public $uri;

    /** If $message starts with a ';', it is appended to the generated message,
     *  otherwise it replaces the generated one. */
    public function __construct(
        $uri,
        $message = null,
        $code = 0,
        \Exception $previous = null
    ) {
        $this->uri = $uri;

        if (!$message || $message[0] == ';') {
            $message =
                "Relative URI \"$uri\" given where absolute URI is needed$message";
        }

        parent::__construct($message, $code, $previous);
    }
}
