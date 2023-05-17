<?php

declare(strict_types=1);

namespace HelpScout\Api\Http\Handlers;

use HelpScout\Api\Exception\ClientException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ClientErrorHandler
{
    public function __invoke(callable $handler)
    {
        return function (RequestInterface $request, array $options = []) use ($handler) {
            return $handler($request, $options)->then(
                function (ResponseInterface $response) use ($request) {
                    if ($response->getStatusCode() >= 400 && $response->getStatusCode() !== 401) {
                        $e = ClientException::create($request, $response);

                        throw $e;
                    }

                    return $response;
                }
            );
        };
    }
}
