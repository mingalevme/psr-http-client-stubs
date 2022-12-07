<?php

declare(strict_types=1);

namespace Mingalevme\PsrHttpClientStubs;

use Mingalevme\Tests\PsrHttpClientStubs\Suites\Unit\HistoryPsrHttpClientDecoratorTest;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @see HistoryPsrHttpClientDecoratorTest
 */
final class HistoryPsrHttpClientDecorator implements ClientInterface
{
    private ClientInterface $psrHttpClient;

    /**
     * @var list<array{
     *     request: RequestInterface,
     *     result: ResponseInterface|ClientExceptionInterface,
     *     response: ?ResponseInterface,
     *     exception: ?ClientExceptionInterface
     * }>
     */
    private array $history = [];

    public function __construct(ClientInterface $psrHttpClient)
    {
        $this->psrHttpClient = $psrHttpClient;
        $this->history = [];
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        try {
            return $this->addResponse($request, $this->psrHttpClient->sendRequest($request));
        } catch (ClientExceptionInterface $e) {
            $this->addException($request, $e);
        }
    }

    private function addResponse(RequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->history[] = [
            'request' => $request,
            'result' => $response,
            'response' => $response,
            'exception' => null,
        ];
        return $response;
    }

    /**
     * @throws ClientExceptionInterface
     * @return never-return
     */
    private function addException(RequestInterface $request, ClientExceptionInterface $e): void
    {
        $this->history[] = [
            'request' => $request,
            'result' => $e,
            'response' => null,
            'exception' => $e,
        ];
        throw $e;
    }

    /**
     * @return list<array{
     *     request: RequestInterface,
     *     result: ResponseInterface|ClientExceptionInterface,
     *     response: ?ResponseInterface,
     *     exception: ?ClientExceptionInterface
     * }>
     */
    public function getHistory(): array
    {
        return $this->history;
    }
}
