<?php

declare(strict_types=1);

namespace Mingalevme\PsrHttpClientStubs;

use Mingalevme\Tests\PsrHttpClientStubs\Suites\Unit\QueuePsrHttpClientTest;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use SplQueue;

/**
 * @see QueuePsrHttpClientTest
 */
final class QueuePsrHttpClient implements ClientInterface
{
    /** @var SplQueue<ResponseInterface|ClientExceptionInterface> */
    private SplQueue $queue;

    /**
     * @param iterable<int, ResponseInterface|ClientExceptionInterface>|null $queue
     */
    public function __construct(?iterable $queue = null)
    {
        /** @psalm-suppress MixedPropertyTypeCoercion */
        $this->queue = new SplQueue();
        foreach (($queue ?: []) as $response) {
            $this->push($response);
        }
    }

    public function push(ResponseInterface|ClientExceptionInterface $result): self
    {
        $this->queue->enqueue($result);
        return $this;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        if ($this->queue->count() === 0) {
            throw new RuntimeException('Unexpected request: queue is empty: ' . $request->getUri()->__toString());
        }
        /**
         * @var ResponseInterface|ClientExceptionInterface $response
         * @psalm-suppress UnnecessaryVarAnnotation
         * @phpstan-ignore-next-line
         */
        $result = $this->queue->dequeue();
        if ($result instanceof ResponseInterface) {
            return $result;
        }
        throw $result;
    }

    /**
     * @return int<0, max>
     */
    public function getQueueLength(): int
    {
        /** @psalm-var int<0, max> $length */
        $length = $this->queue->count();
        return $length;
    }
}
