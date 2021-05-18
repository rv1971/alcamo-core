<?php

namespace alcamo\http;

use alcamo\exception\Closed;
use alcamo\process\Process;
use Laminas\Diactoros\Stream;

class PipeStream extends Stream implements EmitInterface
{
    private $process_;
    private $status_;

    public function __construct(Process $process)
    {
        $this->process_ = $process;
        parent::__construct($process->getStdout());
    }

    public function getStatus(): ?int
    {
        return $this->status_;
    }

    public function close(): void
    {
        if (!$this->resource) {
            return;
        }

        $resource = $this->detach();
        $this->status_ = $this->process_->close();
    }

    public function emit(): ?int
    {
        if (!$this->resource) {
            throw new Closed(get_class($this));
        }

        $count = fpassthru($this->resource);

        return $count === false ? null : $count;
    }
}
