<?php

declare(strict_types=1);

namespace Wotta\SentryTile\Tests\Unit;

use ReflectionObject;
use Illuminate\Support\Facades\Http;
use Wotta\SentryTile\Tests\TestCase;
use Illuminate\Http\Client\PendingRequest;
use Wotta\SentryTile\Exceptions\NoClientTokenSet;

class SentryClientTest extends TestCase
{
    public function test_exception_is_thrown_when_no_api_key_is_set(): void
    {
        $this->expectException(NoClientTokenSet::class);
        $this->expectDeprecationMessage("No sentry api token is set while trying to connect to sentry.\n
            Please visit: https://sentry.io/settings/account/api/");

        Http::prepareClient();
    }

    public function test_client_does_not_throw_an_exception_when_api_key_is_set(): void
    {
        $this->app['config']->set('dashboard.tiles.sentry.token', 'test_api_token');

        $client = Http::prepareClient();

        $this->assertInstanceOf(PendingRequest::class, $client);

        $reflectionClass = new ReflectionObject($client);
        $property = $reflectionClass->getProperty('options');
        $property->setAccessible(true);

        $this->assertEquals('Bearer test_api_token', $property->getValue($client)['headers']['Authorization']);
    }

    public function test_client_can_work_with_a_custom_base_url(): void
    {
        $this->app['config']->set('dashboard.tiles.sentry.token', 'test_api_token');
        $this->app['config']->set('dashboard.tiles.sentry.base_url', 'https://wotty.io/no-api/0/');

        $client = Http::prepareClient();

        $this->assertInstanceOf(PendingRequest::class, $client);

        $reflectionClass = new ReflectionObject($client);
        $property = $reflectionClass->getProperty('baseUrl');
        $property->setAccessible(true);

        $this->assertEquals('https://wotty.io/no-api/0/', $property->getValue($client));
    }
}
