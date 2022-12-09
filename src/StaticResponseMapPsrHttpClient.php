<?php

declare(strict_types=1);

namespace Mingalevme\PsrHttpClientStubs;

use Mingalevme\Tests\PsrHttpClientStubs\Suites\Unit\StaticResponseMapPsrHttpClientTest;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

/**
 * @see StaticResponseMapPsrHttpClientTest
 */
final class StaticResponseMapPsrHttpClient implements ClientInterface
{
    /** @var array<string, ResponseInterface|ClientExceptionInterface> */
    private array $map = [];

    public function add(string $method, string $uri, ResponseInterface|ClientExceptionInterface $result): self
    {
        $this->map[$this->buildKey($method, $uri)] = $result;
        return $this;
    }

    public function addRequest(RequestInterface $request, ResponseInterface|ClientExceptionInterface $result): self
    {
        $this->map[$this->buildKey($request->getMethod(), (string)$request->getUri())] = $result;
        return $this;
    }

    public function remove(string $method, string $uri): self
    {
        unset($this->map[$this->buildKey($method, $uri)]);
        return $this;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $key = $this->buildKey($request->getMethod(), (string)$request->getUri());

        $result = $this->map[$key] ?? null;

        if ($result === null) {
            throw new RuntimeException("Unexpected request: $key");
        }

        if ($result instanceof ResponseInterface) {
            return $result;
        }

        throw $result;
    }

    private function buildKey(string $method, string $uri): string
    {
        return "{$this->normalizeMethodName($method)} $uri";
    }

    private function normalizeMethodName(string $method): string
    {
        return strtolower($method);
    }
}
