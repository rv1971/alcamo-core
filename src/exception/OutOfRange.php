<?php

namespace alcamo\exception;

class OutOfRange extends ValueException
{
    public $lowerBound;
    public $upperBound;

    public static function throwIfOutside(
        $value,
        $lowerBound,
        $upperBound,
        $message = null,
        $code = null
    ) {
        if ($value < $lowerBound || $value > $upperBound) {
            throw new self($value, $lowerBound, $upperBound, $message, $code);
        }
    }

    /** If $message starts with a ';', it is appended to the generated message,
     *  otherwise it replaces the generated one. */
    public function __construct(
        $value,
        $lowerBound,
        $upperBound,
        $message = null,
        $code = 0,
        \Exception $previous = null
    ) {
        $this->lowerBound = $lowerBound;
        $this->upperBound = $upperBound;

        if (!$message || $message[0] == ';') {
            $message =
                "Value \"$value\" out of range [$lowerBound, $upperBound]$message";
        }

        parent::__construct($value, $message, $code, $previous);
    }
}
