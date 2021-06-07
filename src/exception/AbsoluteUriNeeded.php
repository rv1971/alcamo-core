<?php

namespace alcamo\exception;

/**
 * @brief Exception thrown when a relative URI is given where an absolute one
 * would be needed
 *
 * @date Last reviewed 2021-06-07
 */
class AbsoluteUriNeeded extends \UnexpectedValueException
{
    public $uri; ///< URI that triggered the exception

    /**
     * @param $uri @copybrief $uri
     *
     * @param $message If $message starts with a ';', it is appended to the
     *  generated message, otherwise it replaces the generated one.
     */
    public function __construct(
        string $uri,
        string $message = '',
        int $code = 0,
        \Exception $previous = null
    ) {
        $this->uri = $uri;

        if (!$message || $message[0] == ';') {
            $message = "Relative URI \"$uri\" given "
                . "where absolute URI is needed$message";
        }

        parent::__construct($message, $code, $previous);
    }
}
