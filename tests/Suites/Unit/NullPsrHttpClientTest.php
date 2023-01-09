<?php

declare(strict_types=1);

namespace Mingalevme\Tests\PsrHttpClientStubs\Suites\Unit;

use Mingalevme\PsrHttpClientStubs\NullPsrHttpClient;
use Mingalevme\Tests\PsrHttpClientStubs\TestCase;
use RuntimeException;

/**
 * @see NullPsrHttpClient
 */
final class NullPsrHttpClientTest extends TestCase
{
    public function test(): void
    {
        $client = new NullPsrHttpClient();
        $request = $this->getRequestFactory()->createRequest('GET', '/');
        $this->expectException(RuntimeException::class);
        /** @noinspection PhpUnhandledExceptionInspection https://gist.github.com/discordier/ed4b9cba14652e7212f5 */
        $client->sendRequest($request);
    }
}
