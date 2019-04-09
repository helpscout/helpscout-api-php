<?php

declare(strict_types=1);

namespace HelpScout\Api\Http\Handlers;

use HelpScout\Api\Exception\ValidationErrorException;
use HelpScout\Api\Http\Hal\HalDeserializer;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ValidationHandler
{
    public function __invoke(callable $handler)
    {
        return function (RequestInterface $request, array $options = []) use ($handler) {
            return $handler($request, $options)->then(
                function (ResponseInterface $response) use ($request) {
                    if ($response->getStatusCode() === 400 && self::isVndErrorResponse($response)) {
                        $halDocument = HalDeserializer::deserializeDocument((string) $response->getBody());
                        $error = HalDeserializer::deserializeError($halDocument);

                        throw new ValidationErrorException('Validation error', $error, $request, $response);
                    }

                    return $response;
                }
            );
        };
    }

    /**
     * @param ResponseInterface $response
     *
     * @return bool
     */
    private static function isVndErrorResponse(ResponseInterface $response): bool
    {
        if (!$response->hasHeader('Content-Type')) {
            return false;
        }

        return $response->getHeader('Content-Type')[0] === 'application/vnd.error+json';
    }
}
