<?php

namespace Cego\CurlHandleReuseLaravelOctane\Tests\Unit;

use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Http;
use Cego\CurlHandleReuseLaravelOctane\CurlHandleReuseLaravelOctaneServiceProvider;

class HttpTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [CurlHandleReuseLaravelOctaneServiceProvider::class];
    }

    /**
     * Yes, this test relies on the uptime of github.com, but it serves as a smoke test for the Http client.
     *
     * @return void
     */
    public function test_it_can_reach_github(): void
    {
        $response = Http::get('https://github.com');

        $this->assertEquals(200, $response->status());
    }

    public function test_it_can_fake_http_requests(): void
    {
        Http::fake([
            'https://example.com/*' => Http::response(['foo' => 'bar'], 200),
        ]);

        $response = Http::get('https://example.com/foo');

        $this->assertEquals(200, $response->status());
        $this->assertEquals(['foo' => 'bar'], $response->json());
    }
}
