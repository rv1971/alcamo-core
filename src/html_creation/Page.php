<?php

namespace alcamo\html_creation;

use Laminas\Diactoros\Stream;
use alcamo\http\Response;

class Page
{
    private $htmlFactory_; ///< Factory
    private $body_;        ///< Stream
    private $statusCode_;  ///< Integer

    public function __construct(Factory $htmlFactory)
    {
        $this->htmlFactory_ = $htmlFactory;
        $this->body_ = new Stream('php://memory', 'wb+');
        $this->statusCode_ = 200;
    }

    public function getHtmlFactory(): Factory
    {
        return $this->htmlFactory_;
    }

    public function getBody(): Stream
    {
        return $this->body_;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode_;
    }

    public function setStatusCode(int $statusCode)
    {
        $this->statusCode_ = $statusCode;
    }

    public function write(string $htmlData)
    {
        $this->body_->write($htmlData);
    }

    public function getResponse(): Response
    {
        return new Response(
            $this->htmlFactory_->getRdfaData(),
            $this->body_,
            $this->statusCode_
        );
    }

    public function begin(
        ?iterable $resources = null,
        ?Nodes $extraHeadNodes = null
    ) {
        $this->body_->write(
            $this->htmlFactory_['page']
                ->createBegin($resources, $extraHeadNodes)
        );
    }

    public function end(?bool $delayEmission = null)
    {
        $this->body_->write($this->htmlFactory_['page']->createEnd());

        if (!$delayEmission) {
            $this->getResponse()->emit();
        }
    }
}
