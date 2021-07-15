<?php

declare(strict_types=1);

namespace HelpScout\Api\Http;

use Closure;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use HelpScout\Api\ApiClient;
use HelpScout\Api\Entity\Extractable;
use HelpScout\Api\Exception\AuthenticationException;
use HelpScout\Api\Http\Hal\HalDeserializer;
use HelpScout\Api\Http\Hal\HalResource;
use HelpScout\Api\Http\Hal\HalResources;
use HelpScout\Api\Reports\Report;
use Psr\Http\Message\ResponseInterface;

class RestClient
{
    public const BASE_URI = 'https://api.helpscout.net';
    public const CONTENT_TYPE = 'application/json;charset=UTF-8';
    public const CLIENT_USER_AGENT = 'Help Scout PHP API Client/%s (PHP %s)';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var Authenticator
     */
    private $authenticator;

    public function __construct(Client $client, Authenticator $authenticator)
    {
        $this->client = $client;
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
        $request = new Request(
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
        $request = new Request(
            'PUT',
            $uri,
            $this->getDefaultHeaders(),
            $this->encodeEntity($entity)
        );
        $this->send($request);
    }

    public function patchResource(Extractable $entity, string $uri): void
    {
        $request = new Request(
            'PATCH',
            $uri,
            $this->getDefaultHeaders(),
            $this->encodeEntity($entity)
        );
        $this->send($request);
    }

    public function deleteResource(string $uri): void
    {
        $request = new Request(
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
        $request = new Request(
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
        $request = new Request(
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
        $request = new Request(
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
     * @return mixed|ResponseInterface
     */
    private function send(Request $request)
    {
        $options = [
            'base_uri' => self::BASE_URI,
            'http_errors' => false,
        ];

        try {
            $response = $this->client->send($request, $options);
        } catch (AuthenticationException $e) {
            // If the request fails due to an authentication error, retry again after refreshing the token.
            // This allows for token expirations to avoid impacting
            $authenticator = $this->getAuthenticator();
            if ($authenticator->shouldAutoRefreshAccessToken()) {
                $authenticator->fetchAccessAndRefreshToken();
                // Replace the auth headers in the Request object.
                foreach ($this->getAuthHeader() as $header => $value) {
                    $request = $request->withHeader($header, $value);
                }
                $response = $this->client->send($request, $options);
            } else {
                throw $e;
            }
        }

        return $response;
    }
}
