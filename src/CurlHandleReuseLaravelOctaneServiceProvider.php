<?php

namespace Cego\CurlHandleReuseLaravelOctane;

use Illuminate\Http\Client\Factory;
use Illuminate\Support\ServiceProvider;

class CurlHandleReuseLaravelOctaneServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->instance(ReusedCurlHandle::class, new ReusedCurlHandle());
        $this->app->bind(Factory::class, ReusedCurlHandleFactory::class);
    }
}
