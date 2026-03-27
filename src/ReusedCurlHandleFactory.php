<?php

namespace Cego\CurlHandleReuseLaravelOctane;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;

class ReusedCurlHandleFactory extends Factory
{
    public function __construct(
        protected readonly ReusedCurlHandle $reusedCurlHandle,
        ?Dispatcher $dispatcher = null,
    ) {
        parent::__construct($dispatcher);
    }

    protected function newPendingRequest(): PendingRequest
    {
        return parent::newPendingRequest()->setHandler($this->reusedCurlHandle);
    }
}
