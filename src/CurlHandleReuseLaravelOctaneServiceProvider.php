<?php

namespace Cego\CurlHandleReuseLaravelOctane;

use Illuminate\Http\Client\Factory;
use Illuminate\Support\ServiceProvider;

class CurlHandleReuseLaravelOctaneServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/curl-handle-reuse-laravel-octane.php', 'curl-handle-reuse-laravel-octane');
        $this->app->instance(ReusedCurlHandle::class, new ReusedCurlHandle(
            config('curl-handle-reuse-laravel-octane.max_handles', 1000),
        ));
        $this->app->bind(Factory::class, ReusedCurlHandleFactory::class);
    }
}
