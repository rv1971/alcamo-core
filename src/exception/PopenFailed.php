<?php

namespace alcamo\exception;

class PopenFailed extends \RuntimeException
{
    public $command;
    public $mode;

    public function __construct(
        $command,
        ?string $mode = null,
        string $message = '',
        int $code = 0,
        \Exception $previous = null
    ) {
        $this->command = $command;
        $this->mode = $mode;

        if (!$message || $message[0] == ';') {
            $message =
                'Failed to open process "'
                . (is_array($command) ? implode(' ', $command) : $command)
                . '"'
                . (isset($mode) ? " in mode \"$mode\"" : null)
                . $message;
        }

        parent::__construct($message, $code, $previous);
    }
}
