<?php

declare(strict_types=1);

namespace Mingalevme\Tests\PsrHttpClientStubs\Suites\Unit;

use Mingalevme\PsrHttpClientStubs\StaticResponseMapPsrHttpClient;
use Mingalevme\Tests\PsrHttpClientStubs\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use RuntimeException;

/**
 * @see StaticResponseMapPsrHttpClient
 */
final class StaticResponseMapPsrHttpClientTest extends TestCase
{
    /**
     * @throws ClientExceptionInterface
     */
    public function test(): void
    {
        $psrHttpClient = new StaticResponseMapPsrHttpClient();

        $request1 = $this->getRequestFactory()->createRequest('GET', 'https://exmaple.com/1');
        $response1 = $this->getResponseFactory()->createResponse();
        $psrHttpClient->addRequest($request1, $response1);

        $request2 = $this->getRequestFactory()->createRequest('GET', 'https://exmaple.com/2');
        $exception2 = new class () extends \Exception implements ClientExceptionInterface {
        };
        $psrHttpClient->addRequest($request2, $exception2);

        $request3 = $this->getRequestFactory()->createRequest('GET', 'https://exmaple.com/3');

        self::assertSame($response1, $psrHttpClient->sendRequest($request1));

        try {
            $psrHttpClient->sendRequest($request2);
            self::fail();
        } catch (ClientExceptionInterface $e) {
            self::assertSame($exception2, $e);
        }

        try {
            $psrHttpClient->sendRequest($request3);
            self::fail();
        } catch (RuntimeException $e) {
            self::assertSame("Unexpected request: get {$request3->getUri()}", $e->getMessage());
        }
    }
}
