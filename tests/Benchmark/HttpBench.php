<?php

namespace Cego\CurlHandleReuseLaravelOctane\Tests\Benchmark;

use PhpBench\Attributes\Revs;
use Illuminate\Support\Facades\Http;
use PhpBench\Attributes\BeforeMethods;
use Orchestra\Testbench\Concerns\CreatesApplication;

class HttpBench
{
    use CreatesApplication;

    #[BeforeMethods('createApplication'), Revs(50)]
    public function benchHttpWithoutServiceProvider(): void
    {
        Http::get('https://github.com/robots.txt');
    }
}
