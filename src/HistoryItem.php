<?php

declare(strict_types=1);

namespace Mingalevme\PsrHttpClientStubs;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @psalm-immutable
 */
final class HistoryItem
{
    public function __construct(
        private RequestInterface $request,
        private ResponseInterface|ClientExceptionInterface $result,
        private ?ResponseInterface $response,
        private ?ClientExceptionInterface $exception,
    ) {
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    public function getResult(): ResponseInterface|ClientExceptionInterface
    {
        return $this->result;
    }

    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }

    public function getException(): ?ClientExceptionInterface
    {
        return $this->exception;
    }
}
