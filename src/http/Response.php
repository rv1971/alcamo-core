<?php

namespace alcamo\http;

use Laminas\Diactoros\Response as ResponseBase;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use alcamo\rdfa\{HasRdfaDataTrait, RdfaData};

class Response extends ResponseBase
{
    use HasRdfaDataTrait;

    public function __construct(
        RdfaData $rdfaData,
        $body = null,
        ?int $status = null
    ) {
        $this->rdfaData_ = $rdfaData;

        parent::__construct(
            $body ?? 'php://memory',
            $status ?? 200,
            $rdfaData->toHttpHeaders()
        );
    }

    public function computeContentLength(): int
    {
        $length = strlen($this->getBody());

        $this->rdfaData_->replace(
            RdfaData::newFromIterable(['header:content-length' => $length ])
        );

        return $length;
    }

    public function emit()
    {
        $this->computeContentLength();

        (new SapiEmitter())->emit($this);
    }
}
