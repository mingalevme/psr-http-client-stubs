<?php

declare(strict_types=1);

namespace Mingalevme\Tests\PsrHttpClientStubs;

use GuzzleHttp\Psr7\HttpFactory;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected $backupStaticAttributes = false;
    protected $runTestInSeparateProcess = false;

    private function getGuzzleHttpFactory(): HttpFactory
    {
        return new HttpFactory();
    }

    protected function getResponseFactory(): ResponseFactoryInterface
    {
        return $this->getGuzzleHttpFactory();
    }

    protected function getRequestFactory(): RequestFactoryInterface
    {
        return $this->getGuzzleHttpFactory();
    }
}
