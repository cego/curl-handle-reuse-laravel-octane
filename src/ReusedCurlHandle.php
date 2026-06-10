<?php

namespace Cego\CurlHandleReuseLaravelOctane;

use Closure;
use GuzzleHttp\Handler\Proxy;
use GuzzleHttp\Handler\CurlFactory;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\Handler\StreamHandler;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Handler\CurlMultiHandler;
use GuzzleHttp\Promise\PromiseInterface;

class ReusedCurlHandle
{
    private readonly Closure $handler;

    public function __construct(int $maxHandles)
    {
        $this->handler = Closure::fromCallable(self::chooseHandler($maxHandles));
    }

    protected static function chooseHandler(int $maxHandles): callable
    {
        $handler = self::chooseCurlHandler($maxHandles);

        if (\ini_get('allow_url_fopen')) {
            return $handler !== null
                ? Proxy::wrapStreaming($handler, new StreamHandler())
                : new StreamHandler();
        }

        return $handler ?? throw new \RuntimeException(
            'GuzzleHttp requires cURL, the allow_url_fopen ini setting, or a custom HTTP handler.'
        );
    }

    private static function chooseCurlHandler(int $maxHandles): ?callable
    {
        if ( ! self::supportsCurl()) {
            return null;
        }

        if (\function_exists('curl_multi_exec') && \function_exists('curl_exec')) {
            return Proxy::wrapSync(
                new CurlMultiHandler(['handle_factory' => new CurlFactory($maxHandles)]),
                new CurlHandler(['handle_factory' => new CurlFactory($maxHandles)]),
            );
        }

        if (\function_exists('curl_exec')) {
            return new CurlHandler(['handle_factory' => new CurlFactory($maxHandles)]);
        }

        if (\function_exists('curl_multi_exec')) {
            return new CurlMultiHandler(['handle_factory' => new CurlFactory($maxHandles)]);
        }

        return null;
    }

    private static function supportsCurl(): bool
    {
        if ( ! \defined('CURLOPT_CUSTOMREQUEST') || ! \function_exists('curl_version')) {
            return false;
        }

        $curlVersion = \curl_version();

        return \is_array($curlVersion)
            && \is_string($curlVersion['version'] ?? null)
            && \version_compare($curlVersion['version'], '7.21.2') >= 0;
    }

    /**
     * @param array<array-key, mixed> $options
     */
    public function __invoke(RequestInterface $request, array $options): PromiseInterface
    {
        /** @var PromiseInterface */
        return ($this->handler)($request, $options);
    }
}
