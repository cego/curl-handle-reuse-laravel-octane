<?php

namespace Cego\CurlHandleReuseLaravelOctane;

use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Contracts\Events\Dispatcher;

class ReusedCurlHandleFactory extends Factory
{
    public function __construct(
        protected readonly ReusedCurlHandle $reusedCurlHandle,
        ?Dispatcher                         $dispatcher = null,
    ) {
        parent::__construct($dispatcher);
    }

    protected function newPendingRequest(): PendingRequest
    {
        return parent::newPendingRequest()->setHandler($this->reusedCurlHandle);
    }
}
