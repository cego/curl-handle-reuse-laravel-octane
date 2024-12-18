<?php

namespace Cego\CurlHandleReuseLaravelOctane\Tests\Benchmark;

use PhpBench\Attributes\Revs;
use Illuminate\Support\Facades\Http;
use PhpBench\Attributes\BeforeMethods;
use Orchestra\Testbench\Concerns\CreatesApplication;
use Cego\CurlHandleReuseLaravelOctane\CurlHandleReuseLaravelOctaneServiceProvider;

class HttpReusedCurlHandleBench
{
    use CreatesApplication;

    private array $packageProviders = [];

    protected function getPackageProviders($app)
    {
        return $this->packageProviders;
    }

    public function prewarm(): void
    {
        $this->createApplication();
        Http::get('https://github.com/robots.txt');
        Http::get('https://google.com/robots.txt');
    }

    public function prewarmWithServiceProvider(): void
    {
        $this->packageProviders = [CurlHandleReuseLaravelOctaneServiceProvider::class];
        $this->prewarm();
    }

    #[BeforeMethods('prewarm'), Revs(50)]
    public function benchHttpWithoutReusedCurlHandle()
    {
        Http::get('https://ventraip.com.au/robots.txt');
    }

    #[BeforeMethods('prewarmWithServiceProvider'), Revs(50)]
    public function benchHttpWithReusedCurlHandle()
    {
        Http::get('https://ventraip.com.au/robots.txt');
    }

    #[BeforeMethods('prewarmWithServiceProvider'), Revs(50)]
    public function benchHttpWithReusedCurlHandleMultipleOrigins()
    {
        Http::get('https://github.com/robots.txt');
        Http::get('https://google.com/robots.txt');
    }

    #[BeforeMethods('prewarm'), Revs(50)]
    public function benchHttpWithoutReusedCurlHandleMultipleOrigins()
    {
        Http::get('https://github.com/robots.txt');
        Http::get('https://google.com/robots.txt');
    }
}
