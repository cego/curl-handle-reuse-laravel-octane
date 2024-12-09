<?php

namespace Cego\CurlHandleReuseLaravelOctane\Tests\Benchmark;

use Cego\CurlHandleReuseLaravelOctane\CurlHandleReuseLaravelOctaneServiceProvider;
use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\Concerns\CreatesApplication;
use Orchestra\Testbench\TestCase;
use PhpBench\Attributes\BeforeClassMethods;
use PhpBench\Attributes\BeforeMethods;
use PhpBench\Attributes\Revs;

class HttpBench
{
    use CreatesApplication;

    #[BeforeMethods('createApplication'), Revs(50)]
    public function benchHttpWithoutServiceProvider(): void
    {
        Http::get('https://github.com/robots.txt');
    }
}
