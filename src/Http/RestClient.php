<?php

declare(strict_types=1);

namespace HelpScout\Api\Http;

use Closure;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use HelpScout\Api\ApiClient;
use HelpScout\Api\Entity\Extractable;
use HelpScout\Api\Exception\AuthenticationException;
use HelpScout\Api\Exception\ClientException;
use HelpScout\Api\Exception\RateLimitExceededException;
use HelpScout\Api\Exception\ValidationErrorException;
use HelpScout\Api\Http\Hal\HalDeserializer;
use HelpScout\Api\Http\Hal\HalResource;
use HelpScout\Api\Http\Hal\HalResources;
use HelpScout\Api\Reports\Report;
use Http\Discovery\Psr18Client;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class RestClient
{
    public const BASE_URI = 'https://api.helpscout.net';
    public const CONTENT_TYPE = 'application/json;charset=UTF-8';
    public const CLIENT_USER_AGENT = 'Help Scout PHP API Client/%s (PHP %s)';

    /**
     * @var Psr18Client
     */
    private $client;

    /**
     * @var Authenticator
     */
    private $authenticator;

    public function __construct(ClientInterface $client, Authenticator $authenticator)
    {
        $this->client = $client instanceof Psr18Client ? $client : new Psr18Client($client);
        $this->authenticator = $authenticator;
    }

    public function getAuthenticator(): Authenticator
    {
        return $this->authenticator;
    }

    public function getAuthHeader(): array
    {
        return $this->authenticator->getAuthHeader();
    }

    public function getDefaultHeaders(): array
    {
        return array_merge(
            [
                'Content-Type' => self::CONTENT_TYPE,
                'User-Agent' => sprintf(self::CLIENT_USER_AGENT, ApiClient::CLIENT_VERSION, phpversion()),
            ],
            $this->getAuthHeader()
        );
    }

    public function createResource(Extractable $entity, string $uri): ?int
    {
        $request = $this->createRequest(
            'POST',
            $uri,
            $this->getDefaultHeaders(),
            $this->encodeEntity($entity)
        );

        $response = $this->send($request);

        return $response->hasHeader('Resource-ID')
            ? (int) \current($response->getHeader('Resource-ID'))
            : null;
    }

    public function updateResource(Extractable $entity, string $uri): void
    {
        $request = $this->createRequest(
            'PUT',
            $uri,
            $this->getDefaultHeaders(),
            $this->encodeEntity($entity)
        );
        $this->send($request);
    }

    public function patchResource(Extractable $entity, string $uri): void
    {
        $request = $this->createRequest(
            'PATCH',
            $uri,
            $this->getDefaultHeaders(),
            $this->encodeEntity($entity)
        );
        $this->send($request);
    }

    public function deleteResource(string $uri): void
    {
        $request = $this->createRequest(
            'DELETE',
            $uri,
            $this->getDefaultHeaders()
        );
        $this->send($request);
    }

    /**
     * @param Closure|string $entityClass
     */
    public function getResource(
        $entityClass,
        string $uri,
        array $headers = []
    ): HalResource {
        $request = $this->createRequest(
            'GET',
            $uri,
            array_merge($this->getDefaultHeaders(), $headers)
        );
        $response = $this->send($request);
        $halDocument = HalDeserializer::deserializeDocument((string) $response->getBody());

        return HalDeserializer::deserializeResource($entityClass, $halDocument);
    }

    public function getReport(Report $report): array
    {
        $uri = $report->getUriPath();
        $request = $this->createRequest(
            'GET',
            $uri,
            $this->getDefaultHeaders()
        );
        $response = $this->send($request);
        $halDocument = HalDeserializer::deserializeDocument((string) $response->getBody());

        return $halDocument->getData();
    }

    /**
     * @param Closure|string $entityClass
     */
    public function getResources($entityClass, string $rel, string $uri): HalResources
    {
        $request = $this->createRequest(
            'GET',
            $uri,
            $this->getDefaultHeaders()
        );
        $response = $this->send($request);
        $halDocument = HalDeserializer::deserializeDocument((string) $response->getBody());

        return HalDeserializer::deserializeResources($entityClass, $rel, $halDocument);
    }

    /**
     * @throws \JsonException
     */
    private function encodeEntity(Extractable $entity): string
    {
        return json_encode($entity->extract(), JSON_THROW_ON_ERROR);
    }

    /**
     * @return ResponseInterface
     */
    private function send(RequestInterface $request, bool $shouldAutoRefreshAccessToken = true)
    {
        try {
            $response = $this->client->sendRequest($request);

            if ($response->getStatusCode() === 401) {
                throw new AuthenticationException('Invalid Credentials', $request, $response);
            }
            if ($response->getStatusCode() === 429) {
                throw new RateLimitExceededException('Rate limit exceeded', $request, $response);
            }
            if ($response->getStatusCode() === 400 && self::isVndErrorResponse($response)) {
                $halDocument = HalDeserializer::deserializeDocument((string) $response->getBody());
                $error = HalDeserializer::deserializeError($halDocument);

                throw new ValidationErrorException('Validation error', $error, $request, $response);
            }
            if ($response->getStatusCode() >= 400) {
                throw ClientException::create($request, $response);
            }
        } catch (AuthenticationException $e) {
            // If the request fails due to an authentication error, retry again after refreshing the token.
            // This allows for token expirations to avoid impacting
            $authenticator = $this->getAuthenticator();
            if ($shouldAutoRefreshAccessToken && $authenticator->shouldAutoRefreshAccessToken()) {
                $authenticator->fetchAccessAndRefreshToken();
                // Replace the auth headers in the Request object.
                foreach ($this->getAuthHeader() as $header => $value) {
                    $request = $request->withHeader($header, $value);
                }
                $response = $this->send($request, false);
            } else {
                throw $e;
            }
        }

        return $response;
    }

    private function createRequest(string $method, string $uri, array $headers = [], string $body = ''): RequestInterface
    {
        if (!preg_match('{^https?://}', $uri)) {
            $uri = self::BASE_URI . '/' . ltrim($uri, '/');
        }

        $request = $this->client->createRequest($method, $uri);

        foreach ($headers as $header => $value) {
            $request = $request->withHeader($header, $value);
        }

        if ('' !== $body) {
            $request = $request->withBody($this->client->createStream($body));
        }

        return $request;
    }

    private static function isVndErrorResponse(ResponseInterface $response): bool
    {
        if (!$response->hasHeader('Content-Type')) {
            return false;
        }

        return $response->getHeader('Content-Type')[0] === 'application/vnd.error+json';
    }
}
