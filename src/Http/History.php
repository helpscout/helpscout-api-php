<?php

declare(strict_types=1);

namespace HelpScout\Api\Http;

use Http\Client\Common\Plugin\Journal;
use Http\Client\Exception;
use Http\Client\Exception\HttpException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class History implements Journal
{
    /**
     * @var ResponseInterface
     */
    private $lastResponse;

    /**
     * @return ResponseInterface|null
     */
    public function getLastResponse()
    {
        return $this->lastResponse;
    }

    /**
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     */
    public function addSuccess(RequestInterface $request, ResponseInterface $response)
    {
        $this->lastResponse = $response;
    }

    /**
     * @param RequestInterface $request
     * @param Exception        $exception
     */
    public function addFailure(RequestInterface $request, Exception $exception)
    {
        if ($exception instanceof HttpException) {
            $this->lastResponse = $exception->getResponse();
        }
    }
}
