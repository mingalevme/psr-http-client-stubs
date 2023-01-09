<?php

declare(strict_types=1);

namespace Mingalevme\PsrHttpClientStubs;

use Mingalevme\Tests\PsrHttpClientStubs\Suites\Unit\NullPsrHttpClientTest;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

/**
 * @see NullPsrHttpClientTest
 */
final class NullPsrHttpClient implements ClientInterface
{
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        throw new RuntimeException();
    }
}
