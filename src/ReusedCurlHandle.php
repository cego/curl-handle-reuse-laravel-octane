<?php

namespace Cego\CurlHandleReuseLaravelOctane;

use GuzzleHttp\Utils;
use GuzzleHttp\Handler\Proxy;
use GuzzleHttp\Handler\CurlFactory;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\Handler\StreamHandler;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Handler\CurlMultiHandler;
use GuzzleHttp\Promise\PromiseInterface;

class ReusedCurlHandle
{
    /** @var callable(RequestInterface, array<array-key, mixed>): PromiseInterface */
    private $defaultHandler;

    public function __construct(
        int $maxHandles
    ) {
        $this->defaultHandler = self::chooseHandler($maxHandles);
    }

    /**
     * @see Utils::chooseHandler()
     * It is a copy of the original method, besides adding the max handles config for the CurlFactory, CurlMultiHandler does not have a limit per default https://curl.se/libcurl/c/CURLMOPT_MAX_TOTAL_CONNECTIONS.html
     */
    protected static function chooseHandler(int $maxHandles): callable
    {
        $handler = null;

        if (\defined('CURLOPT_CUSTOMREQUEST') && \function_exists('curl_version') && version_compare(curl_version()['version'], '7.21.2') >= 0) {
            if (\function_exists('curl_multi_exec') && \function_exists('curl_exec')) {
                $handler = Proxy::wrapSync(new CurlMultiHandler(['handle_factory' => new CurlFactory($maxHandles)]), new CurlHandler(['handle_factory' => new CurlFactory($maxHandles)]));
            } elseif (\function_exists('curl_exec')) {
                $handler = new CurlHandler(['handle_factory' => new CurlFactory($maxHandles)]);
            } elseif (\function_exists('curl_multi_exec')) {
                $handler = new CurlMultiHandler(['handle_factory' => new CurlFactory($maxHandles)]);
            }
        }

        if (\ini_get('allow_url_fopen')) {
            $handler = $handler
                ? Proxy::wrapStreaming($handler, new StreamHandler())
                : new StreamHandler();
        } elseif ( ! $handler) {
            throw new \RuntimeException('GuzzleHttp requires cURL, the allow_url_fopen ini setting, or a custom HTTP handler.');
        }

        return $handler;
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
