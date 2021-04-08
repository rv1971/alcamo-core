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
        string $message = '',
        int $code = 0
    ) {
        if ($value < $lowerBound || $value > $upperBound) {
            throw new self($value, $lowerBound, $upperBound, $message, $code);
        }
    }

    /** If $message starts with a ';', it is appended to the generated message,
     *  otherwise it replaces the generated one. */
    public function __construct(
        $value,
        $lowerBound = null,
        $upperBound = null,
        $message = null,
        $code = 0,
        \Exception $previous = null
    ) {
        $this->lowerBound = $lowerBound;
        $this->upperBound = $upperBound;

        if (!$message || $message[0] == ';') {
            if (isset($lowerBound)) {
                if (isset($upperBound)) {
                    $interval = "[$lowerBound, $upperBound]";
                } else {
                    $interval = "[$lowerBound, ∞[";
                }
            } else {
                if (isset($upperBound)) {
                    $interval = "]-∞, $upperBound]";
                } else {
                    $interval = "]-∞, ∞[";
                }
            }

            $message = "Value \"$value\" out of range $interval$message";
        }

        parent::__construct($value, $message, $code, $previous);
    }
}
