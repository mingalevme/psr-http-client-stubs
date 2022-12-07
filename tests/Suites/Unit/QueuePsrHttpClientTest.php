<?php

namespace Mingalevme\Tests\PsrHttpClientStubs\Suites\Unit;

use Exception;
use GuzzleHttp\Psr7\HttpFactory;
use Mingalevme\PsrHttpClientStubs\QueuePsrHttpClient;
use Mingalevme\Tests\PsrHttpClientStubs\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use RuntimeException;

/**
 * @see    QueuePsrHttpClient
 * @covers \Mingalevme\PsrHttpClientStubs\QueuePsrHttpClient
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
        $queuePsrHttpClient->push($exception2);

        $request1 = $this->getRequestFactory()->createRequest('GET', '/foo/bar/1');
        $request2 = $this->getRequestFactory()->createRequest('GET', '/foo/bar/2');
        $request3 = $this->getRequestFactory()->createRequest('GET', '/foo/bar/3');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('/foo/bar/3');

        self::assertSame($response1, $queuePsrHttpClient->sendRequest($request1));
        try {
            $queuePsrHttpClient->sendRequest($request2);
            self::fail();
        } catch (ClientExceptionInterface $e) {
            self::assertSame($exception2, $e);
        }
        $queuePsrHttpClient->sendRequest($request3);
    }
}
