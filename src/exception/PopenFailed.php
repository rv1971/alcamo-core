<?php

namespace alcamo\exception;

/**
 * @brief Exception thrown when a call to popen() or proc_open() failed
 *
 * @date Last reviewed 2021-06-07
 */
class PopenFailed extends \RuntimeException
{
    public $command; ///< Command the program attempted to run
    public $mode;    ///< Pipe mode, or `null`

    /**
     * @param $command @copybrief $command
     *
     * @param $mode @copybrief $mode
     *
     * @param $message If $message starts with a ';', it is appended to the
     *  generated message, otherwise it replaces the generated one.
     */
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
