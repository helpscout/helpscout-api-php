<?php

declare(strict_types=1);

namespace HelpScout\Api\Http\Plugin;

use HelpScout\Api\Exception\ValidationErrorException;
use HelpScout\Api\Http\Hal\HalDeserializer;
use Http\Client\Common\Plugin;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ValidationErrorPlugin implements Plugin
{
    /**
     * {@inheritdoc}
     */
    public function handleRequest(RequestInterface $request, callable $next, callable $first)
    {
        $promise = $next($request);

        return $promise->then(function (ResponseInterface $response) use ($request) {
            if ($response->getStatusCode() === 400 && $this->isVndErrorResponse($response)) {
                $halDocument = HalDeserializer::deserializeDocument((string) $response->getBody());
                $error = HalDeserializer::deserializeError($halDocument);

                throw new ValidationErrorException('Validation error', $error, $request, $response);
            }

            return $response;
        });
    }

    /**
     * @param ResponseInterface $response
     *
     * @return bool
     */
    private function isVndErrorResponse(ResponseInterface $response): bool
    {
        if (!$response->hasHeader('Content-Type')) {
            return false;
        }

        return $response->getHeader('Content-Type')[0] === 'application/vnd.error+json';
    }
}
