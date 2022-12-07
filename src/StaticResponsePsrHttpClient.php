<?php

declare(strict_types=1);

namespace Mingalevme\PsrHttpClientStubs;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class StaticResponsePsrHttpClient implements ClientInterface
{
    private ResponseInterface|ClientExceptionInterface $result;

    public function __construct(ResponseInterface|ClientExceptionInterface $result)
    {
        $this->result = $result;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        if ($this->result instanceof ResponseInterface) {
            return $this->result;
        }
        throw $this->result;
    }
}
