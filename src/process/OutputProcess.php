<?php

namespace alcamo\process;

use alcamo\exception\Unsupported;

class OutputProcess extends Process
{
    /**
     * Each of these can be called as a method and will call the php function
     * with this name and the process' stdin as its first parameter.
     */
    public const MAGIC_METHODS = [
        'fputcsv', 'fputs', 'fstat', 'fwrite'
    ];

    public function __call($name, $params)
    {
        if (!in_array($name, static::MAGIC_METHODS)) {
            /** @throw Unsupported is $name is not a supported method */
            throw new Unsupported("$name()");
        }

        return $name($this->pipes_[0], ...$params);
    }
}
