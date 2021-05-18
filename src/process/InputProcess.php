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
        'fstat',
        'stream_get_contents',
        'stream_get_line',
        'stream_get_meta_data'
    ];

    public function __call($name, $params)
    {
        if (!in_array($name, static::MAGIC_METHODS)) {
            /** @throw Unsupported is $name is not a supported method */
            throw new Unsupported("$name()");
        }

        return $name($this->pipes_[1], ...$params);
    }
}
