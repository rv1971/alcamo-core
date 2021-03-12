<?php

namespace alcamo\http;

use alcamo\exception\{Closed, PopenFailed};
use Laminas\Diactoros\Stream;

class PipeStream extends Stream implements EmitInterface
{
    private $status_;

    public function __construct(string $command, string $mode)
    {
        $resource = popen($command, $mode);

        if ($resource === false) {
            throw new PopenFailed($command, $mode);
        }

        parent::__construct($resource);
    }

    public function getStatus(): ?int
    {
        return $this->status_;
    }

    public function close(): void
    {
        if (! $this->resource) {
            return;
        }

        $resource = $this->detach();
        $this->status_ = pclose($resource);
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
