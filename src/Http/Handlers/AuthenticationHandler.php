<?php

declare(strict_types=1);

namespace HelpScout\Api\Http\Handlers;

use HelpScout\Api\Exception\AuthenticationException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class AuthenticationHandler
{
    public function __invoke(callable $handler)
    {
        return function (RequestInterface $request, array $options = []) use ($handler) {
            return $handler($request, $options)->then(
                function (ResponseInterface $response) use ($request) {
                    if ($response->getStatusCode() === 401) {
                        throw new AuthenticationException('Invalid Credentials', $request, $response);
                    }

                    return $response;
                }
            );
        };
    }
}
