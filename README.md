# Curl Handle Reuse for Laravel Octane

# Motivation

Per default in most PHP applications, a new curl handle is created for each outgoing request, which will require a new TCP connection to be established, and a TLS handshake to be performed if the request is made over HTTPS.

The TLS handshake can be a big performance penalty for applications that strive for low latency, as it requires 2 round trips between the client and the server for the TLS handshake, and one round trip for the TCP connection. This can easily reach 100ms and more depending on your round trip latency to the server.

# Solution

This package, by default, replaces the bind for the Http facade, to use the same curl handle for all outgoing requests.

It works by binding a Guzzle handle in the service container as a "true"-singleton. To reuse across requests, worker mode must be used, such as in Laravel Octane. 

It then automatically rebinds the Http facade to automatically use this singleton, so you don't have to change your code. It is a minimally invasive change to the Http client, and no code changes should be required to use it.

You can also use the bound Guzzle handle in other HTTP clients, by resolving `\Cego\CurlHandleReuseLaravelOctane\ReusedCurlHandle::class` from the service container.

# Installation

```bash
composer require cego/curl-handle-reuse-laravel-octane
```

# Contributing
Feel free to open issues or pull requests if you have any suggestions or improvements. Remember to run php-cs-fixer, unittests and phpstan before opening a pull request.

# Performance
You can expect to reduce the latency of outgoing requests by 3 times the latency roundtrip. The speedup factor will depend on the latency of the server you are connecting to, compared to the execution time of the endpoint you are calling.

For example if you have a server with a 100ms roundtrip latency, and the endpoint takes 100ms to execute, then you are likely to see a speedup from 400ms to 200ms.

## Benchmarks
See tests/Benchmark for a simple PHPBench setup that pings github.com/robots.txt 50 times with and without the package. With the package, a prewarmed curl handle is used. The round-trip latency was measured to approximately 19.5ms.

```
+---------------------------+----------------------------------------------------+----------+-----------+-----------+-----------+--------+---------+
| benchmark                 | subject                                            | memory   | min       | max       | mode      | rstdev | stdev   |
+---------------------------+----------------------------------------------------+----------+-----------+-----------+-----------+--------+---------+
| HttpReusedCurlHandleBench | benchHttpWithoutReusedCurlHandle ()                | 16.581mb | 77.222ms  | 77.222ms  | 77.222ms  | ±0.00% | 0.000μs |
| HttpReusedCurlHandleBench | benchHttpWithReusedCurlHandle ()                   | 16.607mb | 20.812ms  | 20.812ms  | 20.812ms  | ±0.00% | 0.000μs |
| HttpReusedCurlHandleBench | benchHttpWithReusedCurlHandleMultipleOrigins ()    | 18.103mb | 47.237ms  | 47.237ms  | 47.237ms  | ±0.00% | 0.000μs |
| HttpReusedCurlHandleBench | benchHttpWithoutReusedCurlHandleMultipleOrigins () | 18.078mb | 181.377ms | 181.377ms | 181.377ms | ±0.00% | 0.000μs |
+---------------------------+----------------------------------------------------+----------+-----------+-----------+-----------+--------+---------+
```

As can be seen, the benchmark of querying github.com/robots.txt 50 times is reduced from 77.222ms to 20.812ms, which is approximately 3x roundtrip latency of 19.5ms. (it would be equivalent to roundtrip latency of 18.8ms)


## See also
Aaron Francis has covered this topic in a video on his YouTube channel, where he explains the performance benefits of reusing curl handles in Laravel Octane. You can watch the video here:
https://www.youtube.com/watch?v=BWAocgJVCbw
