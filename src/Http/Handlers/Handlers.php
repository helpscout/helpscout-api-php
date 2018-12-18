<?php

declare(strict_types=1);

namespace HelpScout\Api\Http\Handlers;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Middleware;
use HelpScout\Api\Exception\RateLimitExceededException;
use HelpScout\Api\Exception\ValidationErrorException;
use HelpScout\Api\Http\Hal\HalDeserializer;
use Psr\Http\Message\ResponseInterface;

class Handlers
{
    public static function rateLimit(): callable
    {
        $handler = function ($request, $options, $promise) {
            return $promise->then(function (ResponseInterface $response) use ($request) {
                if ($response->getStatusCode() === 429) {
                    throw new RateLimitExceededException('Rate limit exceeded', $request, $response);
                }

                return $response;
            });
        };

        return Middleware::tap(null, $handler);
    }

    public static function clientError(): callable
    {
        $handler = function ($request, $options, $promise) {
            return $promise->then(function (ResponseInterface $response) use ($request) {
                if ($response->getStatusCode() >= 400) {
                    $e = RequestException::create($request, $response);

                    throw $e;
                }

                return $response;
            });
        };

        return Middleware::tap(null, $handler);
    }

    public static function validation(): callable
    {
        $handler = function ($request, $options, $promise) {
            return $promise->then(function (ResponseInterface $response) use ($request) {
                if ($response->getStatusCode() === 400 && self::isVndErrorResponse($response)) {
                    $halDocument = HalDeserializer::deserializeDocument((string) $response->getBody());
                    $error = HalDeserializer::deserializeError($halDocument);

                    throw new ValidationErrorException('Validation error', $error, $request, $response);
                }

                return $response;
            });
        };

        return Middleware::tap(null, $handler);
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
