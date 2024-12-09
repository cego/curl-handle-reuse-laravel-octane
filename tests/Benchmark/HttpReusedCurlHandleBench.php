<?php

namespace Cego\CurlHandleReuseLaravelOctane\Tests\Benchmark;

use Cego\CurlHandleReuseLaravelOctane\CurlHandleReuseLaravelOctaneServiceProvider;
use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\Concerns\CreatesApplication;
use PhpBench\Attributes\BeforeMethods;
use PhpBench\Attributes\Iterations;
use PhpBench\Attributes\Revs;

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
