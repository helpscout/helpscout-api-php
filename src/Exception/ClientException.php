<?php

declare(strict_types=1);

namespace HelpScout\Api\Exception;

use GuzzleHttp\BodySummarizerInterface;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ClientException extends RequestException implements Exception
{
    /**
     * @internal
     */
    public static function create(RequestInterface $request, ResponseInterface $response = null, \Throwable $previous = null, array $handlerContext = [], BodySummarizerInterface $bodySummarizer = null): self
    {
        $e = parent::create($request, $response);

        return new self($e->getMessage(), $request, $response, $previous, $handlerContext);
    }
}
