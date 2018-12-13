<?php

declare(strict_types=1);

namespace HelpScout\Api\Http\Plugin;

use HelpScout\Api\Exception\RateLimitExceededException;
use Http\Client\Common\Plugin;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class RateLimitErrorPlugin implements Plugin
{
    /**
     * {@inheritdoc}
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first)
    {
        $promise = $next($request);

        return $promise->then(function (ResponseInterface $response) use ($request) {
            if ($response->getStatusCode() === 429) {
                throw new RateLimitExceededException('Rate limit exceeded', $request, $response);
            }

            return $response;
        });
    }
}
