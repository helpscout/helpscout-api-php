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
     * @param string $message
     */
    public function __construct(
        $message,
        VndError $error,
        RequestInterface $request,
        ResponseInterface $response,
        \Exception $previous = null
    ) {
        // Append some details on what steps to take to see the underlying validation problems are.
        $message = $message.' - use getError() to see the underlying validation issues';

        parent::__construct($message, $request, $response, $previous);

        $this->error = $error;
    }

    public function getError(): VndError
    {
        return $this->error;
    }
}
