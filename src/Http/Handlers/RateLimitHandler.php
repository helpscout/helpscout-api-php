<?php

declare(strict_types=1);

namespace HelpScout\Api\Http\Handlers;

use HelpScout\Api\Exception\RateLimitExceededException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class RateLimitHandler
{
    public function __invoke(callable $handler)
    {
        return function (RequestInterface $request, array $options = []) use ($handler) {
            return $handler($request, $options)->then(
                function (ResponseInterface $response) use ($request) {
                    if ($response->getStatusCode() === 429) {
                        throw new RateLimitExceededException('Rate limit exceeded', $request, $response);
                    }

                    return $response;
                }
            );
        };
    }
}
