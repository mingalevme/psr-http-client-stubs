<?php

declare(strict_types=1);

namespace Mingalevme\Tests\PsrHttpClientStubs\Suites\Unit;

use Exception;
use Mingalevme\PsrHttpClientStubs\StaticResponsePsrHttpClient;
use Mingalevme\Tests\PsrHttpClientStubs\TestCase;
use Psr\Http\Client\ClientExceptionInterface;

/**
 * @see    StaticResponsePsrHttpClient
 * @covers \Mingalevme\PsrHttpClientStubs\StaticResponsePsrHttpClient
 */
final class StaticResponsePsrHttpClientTest extends TestCase
{
    /**
     * @throws ClientExceptionInterface
     */
    public function testResponse(): void
    {
        $request = $this->getRequestFactory()->createRequest('GET', '/');
        $response = $this->getResponseFactory()->createResponse();
        $client = new StaticResponsePsrHttpClient($response);
        self::assertSame($response, $client->sendRequest($request));
        self::assertSame($response, $client->sendRequest($request));
    }

    public function testException(): void
    {
        $request = $this->getRequestFactory()->createRequest('GET', '/');
        $exception = new class () extends Exception implements ClientExceptionInterface {
        };
        $client = new StaticResponsePsrHttpClient($exception);
        try {
            $client->sendRequest($request);
            self::fail();
        } catch (ClientExceptionInterface $e) {
            self::assertSame($exception, $e);
        }
        try {
            $client->sendRequest($request);
            self::fail();
        } catch (ClientExceptionInterface $e) {
            self::assertSame($exception, $e);
        }
    }
}
