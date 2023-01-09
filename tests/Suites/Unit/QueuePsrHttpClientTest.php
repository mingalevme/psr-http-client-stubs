<?php

namespace Mingalevme\Tests\PsrHttpClientStubs\Suites\Unit;

use Exception;
use GuzzleHttp\Psr7\HttpFactory;
use Mingalevme\PsrHttpClientStubs\QueuePsrHttpClient;
use Mingalevme\Tests\PsrHttpClientStubs\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use RuntimeException;

/**
 * @see QueuePsrHttpClient
 */
final class QueuePsrHttpClientTest extends TestCase
{
    /**
     * @throws ClientExceptionInterface
     */
    public function test(): void
    {
        $guzzleHttpFactory = new HttpFactory();

        $response1 = $guzzleHttpFactory->createResponse();
        $exception2 = new class () extends Exception implements ClientExceptionInterface {
        };

        $queuePsrHttpClient = new QueuePsrHttpClient([$response1]);
        self::assertSame(1, $queuePsrHttpClient->getQueueLength());
        $queuePsrHttpClient->push($exception2);
        self::assertSame(2, $queuePsrHttpClient->getQueueLength());

        $request1 = $this->getRequestFactory()->createRequest('GET', '/foo/bar/1');
        $request2 = $this->getRequestFactory()->createRequest('GET', '/foo/bar/2');
        $request3 = $this->getRequestFactory()->createRequest('GET', '/foo/bar/3');

        self::assertSame($response1, $queuePsrHttpClient->sendRequest($request1));
        self::assertSame(1, $queuePsrHttpClient->getQueueLength());

        try {
            $queuePsrHttpClient->sendRequest($request2);
            self::fail();
        } catch (ClientExceptionInterface $e) {
            self::assertSame($exception2, $e);
        }
        self::assertSame(0, $queuePsrHttpClient->getQueueLength());

        try {
            $queuePsrHttpClient->sendRequest($request3);
            self::fail();
        } catch (RuntimeException $e) {
            self::assertStringContainsString('/foo/bar/3', $e->getMessage());
        }
        self::assertSame(0, $queuePsrHttpClient->getQueueLength());
    }
}
