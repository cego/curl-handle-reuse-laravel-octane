# Curl Handle Reuse for Laravel Octane

# Motivation

Per default in most PHP applications, a new curl handle is created for each outgoing request, which will require a new TCP connection to be established, and a TLS handshake to be performed if the request is made over HTTPS.

The TLS handshake can be a big performance penalty for applications that strive for low latency, as it requires 2 round trips between the client and the server. This can easily reach 100ms and more depending on your round trip latency to the server.

# Solution

This package, by default, replaces the bind for the Http facade, to use the same curl handle for all outgoing requests.

It works by binding a Guzzle handle in the service container as a singleton. To reuse across requests, worker mode must be used, such as in Laravel Octane.

You can also use the bound Guzzle handle in other HTTP clients, by resolving `\Cego\CurlHandleReuseLaravelOctane\ReusedCurlHandle::class` from the service container.

# Installation

```bash
composer require cego/curl-handle-reuse-laravel-octane
```

# Performance
You can expect to reduce the latency of outgoing requests by 2 times the latency roundtrip. The speedup factor will depend on the latency of the server you are connecting to, compared to the execution time of the endpoint you are calling.

For example if you have a server with a 100ms roundtrip latency, and the endpoint takes 100ms to execute, then you are likely to see a speedup from 400ms to 200ms.

## See also
Aaron Francis has covered this topic in a video on his YouTube channel, where he explains the performance benefits of reusing curl handles in Laravel Octane. You can watch the video here:
https://www.youtube.com/watch?v=BWAocgJVCbw
