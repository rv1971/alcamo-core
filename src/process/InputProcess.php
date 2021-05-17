<?php

namespace alcamo\process;

use alcamo\exception\Unsupported;

class InputProcess extends Process
{
    /**
     * Each of these can be called as a method and will call the php function
     * with this name and the process' stdout as its first parameter.
     */
    public const MAGIC_METHODS = [
        'fgetc',
        'fgetcsv',
        'fgets',
        'fgetss',
        'fpassthru',
        'fread',
        'fscanf',
        'fstat'
    ];

    public function __call($name, $params)
    {
        if (!in_array($name, static::MAGIC_METHODS)) {
            throw new Unsupported($name);
        }

        return $name($this->pipe_[1], ...$params);
    }
}
