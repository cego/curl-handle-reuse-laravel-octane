<?php

namespace Cego\CurlHandleReuseLaravelOctane;

use GuzzleHttp\Utils;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Promise\PromiseInterface;

class ReusedCurlHandle
{
    /** @var callable(RequestInterface, array<array-key, mixed>): PromiseInterface */
    private $defaultHandler;

    public function __construct(
    ) {
        $this->defaultHandler = Utils::chooseHandler();
    }

    /**
     * @param RequestInterface $request
     * @param array<array-key, mixed> $options
     *
     * @return PromiseInterface
     */
    public function __invoke(RequestInterface $request, array $options)
    {
        return call_user_func($this->defaultHandler, $request, $options);
    }
}
