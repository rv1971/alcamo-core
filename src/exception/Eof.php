<?php

namespace alcamo\exception;

class Eof extends AbstractObjectStateException
{
    public const MESSAGE_INCIPIT = 'Eof in';

    public $requestedUnits;
    public $availableUnits;

    /**
     * @param $objectOrLabel Either an object or a string describing a
     *  variable.
     *
     * If $message starts with a ';', it is appended to the generated message,
     * otherwise it replaces the generated one.
     */
    public function __construct(
        $objectOrLabel,
        ?int $requestedUnits = null,
        ?int $availableUnits = null,
        string $message = '',
        int $code = 0,
        \Exception $previous = null
    ) {
        $this->requestedUnits = $requestedUnits;
        $this->availableUnits = $availableUnits;


        if (!$message || $message[0] == ';') {
            $message = static::MESSAGE_INCIPIT . ' '
                . (is_object($objectOrLabel)
                   ? get_class($objectOrLabel)
                   : $objectOrLabel)
                . (isset($requestedUnits)
                   ? ": requested $requestedUnits units"
                   : '')
                . (isset($availableUnits)
                   ? ", available $availableUnits"
                   : '')
                . $message;
        }

        parent::__construct($objectOrLabel, $message, $code, $previous);
    }
}
