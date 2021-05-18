<?php

namespace alcamo\process;

use alcamo\exception\DirectoryNotFound;

class ProcessFactory
{
    /// to be overridden in derived classes
    public const DEFAULT_BINARY = 'false';

    public const PROCESS_CLASS = Process::class;

    private $dir_;     ///< ?string
    private $program_; ///< string
    private $options_; ///< array
    private $env_;     ///< ?array

    public function __construct(
        ?string $dir = null,
        ?string $program = null,
        ?array $options = null,
        ?array $env = null
    ) {
        if (isset($dir)) {
            $this->dir_ = realpath($dir);

            if ($this->dir_ === false) {
                /** @throw DirectoryNotFound if `realpath($dir)` fails */
                throw new DirectoryNotFound($dir);
            }
        }

        $this->program_ = $program ?? static::DEFAULT_BINARY;
        $this->options_ = (array)$options;
        $this->env_ = $env;
    }

    public function getDir(): ?string
    {
        return $this->dir_;
    }

    public function getProgram(): string
    {
        return $this->program_;
    }

    public function getOptions(): array
    {
        return $this->options_;
    }

    public function getEnv(): ?array
    {
        return $this->env_;
    }

    public function exec($args = null): Process
    {
        $cmd = array_merge([ $this->program_ ], $this->options_, (array)$args);

        $class = static::PROCESS_CLASS;

        return new $class($cmd, $this->dir_, $this->env_);
    }
}
