<?php

declare(strict_types=1);

namespace HelpScout\Api\Exception;

use GuzzleHttp\Exception\RequestException;
use HelpScout\Api\Http\Hal\VndError;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class ValidationErrorException extends RequestException implements Exception
{
    /**
     * @var VndError
     */
    private $error;

    /**
     * @param string            $message
     * @param VndError          $error
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param \Exception|null   $previous
     */
    public function __construct(
        $message,
        VndError $error,
        RequestInterface $request,
        ResponseInterface $response,
        \Exception $previous = null
    ) {
        parent::__construct($message, $request, $response, $previous);

        $this->error = $error;
    }

    /**
     * @return VndError
     */
    public function getError(): VndError
    {
        return $this->error;
    }
}
