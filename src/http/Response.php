<?php

namespace alcamo\http;

use Laminas\Diactoros\Response as ResponseBase;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use alcamo\rdfa\{HasRdfaDataTrait, RdfaData};

class Response extends ResponseBase
{
    use HasRdfaDataTrait;

    public static function newFromStatusAndText(
        int $status,
        ?string $text = null,
        $rdfaData = null
    ) {
        $autoRdfaData =
            RdfaData::newFromIterable([ 'dc:format' => 'text/plain' ]);

        if ($rdfaData instanceof RdfaData) {
            $rdfaData = $autoRdfaData->replace($rdfaData);
        } elseif (isset($rdfaData)) {
            $rdfaData =
                $autoRdfaData->replace(RdfaData::newFromIterable($rdfaData));
        } else {
            $rdfaData = $autoRdfaData;
        }

        $response = new self($rdfaData, null, $status);

        if (isset($text)) {
            $response->getBody()->write($text);
        } else {
            $response->getBody()->write($response->getReasonPhrase());
        }

        return $response;
    }

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
        (new SapiEmitter())->emit(
            $this->withHeader('Content-Length', $this->computeContentLength())
        );
    }
}
