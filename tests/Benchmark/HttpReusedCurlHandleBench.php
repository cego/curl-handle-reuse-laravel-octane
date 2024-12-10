<?php

namespace Cego\CurlHandleReuseLaravelOctane\Tests\Benchmark;

use PhpBench\Attributes\Revs;
use Illuminate\Support\Facades\Http;
use PhpBench\Attributes\BeforeMethods;
use Orchestra\Testbench\Concerns\CreatesApplication;
use Cego\CurlHandleReuseLaravelOctane\CurlHandleReuseLaravelOctaneServiceProvider;

class HttpReusedBench
{
    use CreatesApplication;

    protected function getPackageProviders($app)
    {
        return [CurlHandleReuseLaravelOctaneServiceProvider::class];
    }

    public function prewarm(): void
    {
        $this->createApplication();
        Http::get('https://github.com/robots.txt');
    }

    #[BeforeMethods('prewarm'), Revs(50)]
    public function benchHttpWithReusedCurlHandle()
    {
        Http::get('https://github.com/robots.txt');
    }
}
