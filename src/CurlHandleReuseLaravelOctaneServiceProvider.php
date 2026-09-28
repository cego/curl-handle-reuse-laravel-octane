<?php

namespace Cego\CurlHandleReuseLaravelOctane;

use InvalidArgumentException;
use Illuminate\Http\Client\Factory;
use Illuminate\Support\ServiceProvider;

class CurlHandleReuseLaravelOctaneServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/curl-handle-reuse-laravel-octane.php', 'curl-handle-reuse-laravel-octane');

        $this->app->instance(ReusedCurlHandle::class, new ReusedCurlHandle(
            $this->integer('curl-handle-reuse-laravel-octane.max_handles'),
            $this->integer('curl-handle-reuse-laravel-octane.max_seconds_per_connection'),
        ));

        $this->app->bind(Factory::class, ReusedCurlHandleFactory::class);
    }

    private function integer(string $key): int
    {
        $value = config($key);

        if ( ! \is_int($value)) {
            throw new InvalidArgumentException(\sprintf('Configuration value for key [%s] must be an integer, %s given.', $key, \gettype($value)));
        }

        return $value;
    }
}
