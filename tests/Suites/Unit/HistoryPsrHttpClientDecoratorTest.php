<?php

declare(strict_types=1);

namespace Mingalevme\Tests\PsrHttpClientStubs\Suites\Unit;

use Exception;
use Mingalevme\PsrHttpClientStubs\HistoryPsrHttpClientDecorator;
use Mingalevme\PsrHttpClientStubs\QueuePsrHttpClient;
use Mingalevme\Tests\PsrHttpClientStubs\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use RuntimeException;

/**
 * @see HistoryPsrHttpClientDecorator
 */
final class HistoryPsrHttpClientDecoratorTest extends TestCase
{
    /**
     * @throws ClientExceptionInterface
     */
    public function test(): void
    {
        $request1 = $this->getRequestFactory()->createRequest('GET', '/test1');
        $response1 = $this->getResponseFactory()->createResponse();
        $request2 = $this->getRequestFactory()->createRequest('GET', '/test2');
        $exception2 = new class () extends Exception implements ClientExceptionInterface {
        };
        $request3 = $this->getRequestFactory()->createRequest('GET', '/test3');
        $psrHttpClient = new QueuePsrHttpClient([
            $response1,
            $exception2,
        ]);
        $decorator = new HistoryPsrHttpClientDecorator($psrHttpClient);
        self::assertSame($response1, $decorator->sendRequest($request1));
        try {
            $decorator->sendRequest($request2);
            self::fail();
        } catch (ClientExceptionInterface $e) {
            self::assertSame($exception2, $e);
        }
        try {
            $decorator->sendRequest($request3);
            self::fail();
        } catch (Exception $e) {
            self::assertInstanceOf(RuntimeException::class, $e);
        }
        $history = $decorator->getHistory();
        self::assertCount(2, $history);
        // 1
        self::assertSame($request1, $history[0]->getRequest());
        self::assertSame($response1, $history[0]->getResult());
        self::assertSame($response1, $history[0]->getResponse());
        self::assertSame(null, $history[0]->getException());
        // 2
        self::assertSame($request2, $history[1]->getRequest());
        self::assertSame($exception2, $history[1]->getResult());
        self::assertSame(null, $history[1]->getResponse());
        self::assertSame($exception2, $history[1]->getException());
        // Clear
        self::assertCount(2, $decorator->getHistory());
    }
}
