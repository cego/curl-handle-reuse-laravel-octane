<?php

namespace Cego\CurlHandleReuseLaravelOctane;

use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Contracts\Events\Dispatcher;

class ReusedCurlHandleFactory extends Factory
{
    /**
     * Create a new factory instance.
     *
     * @param  ReusedCurlHandle  $reusedCurlHandle
     * @param Dispatcher|null $dispatcher
     *
     * @return void
     */
    public function __construct(protected ReusedCurlHandle $reusedCurlHandle, ?Dispatcher $dispatcher = null)
    {
        parent::__construct($dispatcher);
    }

    /**
     * Instantiate a new pending request instance for this factory.
     *
     * @return PendingRequest
     */
    protected function newPendingRequest()
    {
        return parent::newPendingRequest()->setHandler($this->reusedCurlHandle);
    }
}
