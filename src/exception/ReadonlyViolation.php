<?php

namespace alcamo\exception;

/**
 * @brief Exception thrown by an attempt to write to a readonly object.
 *
 * @date Last reviewed 2021-06-07
 */
class ReadonlyViolation extends \LogicException
{
    public $object; ///< Object, or `null`
    public $method; ///< Method name, or `null`

    /**
     * @param $object @copybrief $object
     * If not given, extracted from the backtrace.
     *
     * @param $method @copybrief $method
     * If not given, extracted from the backtrace.
     *
     * @param $message If $message starts with a ';', it is appended to the
     * automatically generated message, otherwise it replaces the generated
     * one.
     */
    public function __construct(
        ?object $object = null,
        ?string $method = null,
        string $message = '',
        int $code = 0,
        \Exception $previous = null
    ) {
        $this->object = $object ?? \debug_backtrace()[1]['object'];

        $this->method = $method ?? \debug_backtrace()[1]['function'];

        if (!$message || $message[0] == ';') {
            $message = "Attempt to modify readonly " . get_class($this->object)
                . " object through {$this->method}()"
                . $message;
        }

        parent::__construct($message, $code, $previous);
    }
}
