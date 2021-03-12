<?php

namespace alcamo\exception;

class PopenFailed extends \RuntimeException
{
    public $command;
    public $mode;

    public function __construct(
        $command,
        $mode = null,
        $message = '',
        $code = 0,
        \Exception $previous = null
    ) {
        $this->command = $command;
        $this->mode = $mode;

        if (!$message || $message[0] == ';') {
            $message =
                "Failed to open process \"$command\" in mode \"$mode\""
                . $message;
        }

        parent::__construct($message, $code, $previous);
    }
}
