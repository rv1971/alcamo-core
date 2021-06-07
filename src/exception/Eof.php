<?php

namespace alcamo\exception;

/**
 * @brief Exception thrown when an end of file occurs in an object
 *
 * @date Last reviewed 2021-06-07
 */
class Eof extends AbstractObjectStateException
{
    /** @copydoc AbstractObjectStateException::MESSAGE_INCIPIT */
    public const MESSAGE_INCIPIT = 'Eof in';

    public $requestedUnits; ///< Data units the program attempted to extract
    public $availableUnits; ///< Data units available

    /**
     * @param $objectOrLabel
     * @copybrief AbstractObjectStateException::$objectOrLabel
     *
     * @param $requestedUnits @copybrief $requestedUnits
     *
     * @param $availableUnits @copybrief $availableUnits
     *
     * @param $message If $message starts with a ';', it is appended to the
     * generated message, otherwise it replaces the generated one.
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
