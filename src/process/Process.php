<?php

namespace alcamo\process;

use alcamo\exception\{Closed, DirectoryNotFound, Opened, PopenFailed};

class Process
{
    private $cmd_; ///< string|array
    private $dir_; ///< ?string
    private $env_; ///< ?array

    protected $pipes_;   ///< ?array
    protected $process_; ///< ?resource

    public function __construct(
        $cmd,
        ?string $dir,
        ?array $env,
        ?bool $deferOpen = null
    ) {
        $this->cmd_ = $cmd;

        if (isset($dir)) {
            $this->dir_ = realpath($dir);

            if ($this->dir_ === false) {
                /** @throw DirectoryNotFound if `realpath($dir)´ fails */
                throw new DirectoryNotFound($dir);
            }
        }

        $this->env_ = $env;

        if (!$deferOpen) {
            $this->open();
        }
    }

    public function getCmd()
    {
        return $this->cmd_;
    }

    public function getDir(): ?string
    {
        return $this->dir_;
    }

    public function getEnv(): ?array
    {
        return $this->env_;
    }

    public function open()
    {
        if (isset($this->process_)) {
            /** @throw Opened if process is already opened */
            throw new Opened($this->process_);
        }

        $this->process_ = proc_open(
            $this->cmd_,
            $this->createDescriptorSpec(),
            $this->pipes_,
            $this->dir_,
            $this->env_,
            $this->createOtherOptions()
        );

        if ($this->process_ === false) {
            /** @throw PopenFailed if proc_open() fails */
            throw new PopenFailed($this->cmd_);
        }
    }

    public function close(): int
    {
        if (!isset($this->process_)) {
            /** @throw Closed if process is already closed */
            throw new Closed('process');
        }

        $exitcode = proc_close($this->process_);

        $this->process_ = null;
        $this->pipes_ = null;

        return $exitcode;
    }

    public function getStdin()
    {
        return $this->pipes_[0];
    }

    public function getStdout()
    {
        return $this->pipes_[1];
    }

    public function getStderr()
    {
        return $this->pipes_[2];
    }

    protected function createDescriptorSpec(): array
    {
        return [
            0 => [ 'pipe', 'w' ],
            1 => [ 'pipe', 'r' ],
            2 => [ 'pipe', 'r' ]
        ];
    }

    protected function createOtherOptions(): ?array
    {
        return null;
    }
}
