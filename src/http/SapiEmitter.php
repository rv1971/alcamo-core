<?php

namespace alcamo\http;

use Laminas\HttpHandlerRunner\Emitter\{EmitterInterface, SapiEmitterTrait};
use Psr\Http\Message\ResponseInterface;

class SapiEmitter implements EmitterInterface
{
    use SapiEmitterTrait;

    public function emit(
        ResponseInterface $response,
        ?bool $sendContentLength = null
    ): bool {
        $this->assertNoPreviousOutput();

        $this->emitHeaders($response);

        $this->emitStatusLine($response);

        if ($sendContentLength) {
            $body = (string)$response->getBody();

            header('Content-Length: ' . strlen($body));

            echo $body;
        } else {
            if ($response->getBody() instanceof EmitInterface) {
                $response->getBody()->emit();
            } else {
                echo $response->getBody();
            }
        }

        return true;
    }
}
