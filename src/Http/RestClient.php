<?php

declare(strict_types=1);

namespace HelpScout\Api\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use HelpScout\Api\ApiClient;
use HelpScout\Api\Entity\Extractable;
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

    /**
     * @param Client        $client
     * @param Authenticator $authenticator
     */
    public function __construct(Client $client, Authenticator $authenticator)
    {
        $this->client = $client;
        $this->authenticator = $authenticator;
    }

    /**
     * @return Authenticator
     */
    public function getAuthenticator(): Authenticator
    {
        return $this->authenticator;
    }

    /**
     * @return array
     */
    public function getAuthHeader(): array
    {
        return $this->authenticator->getAuthHeader();
    }

    /**
     * @return array
     */
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

    /**
     * @param Extractable $entity
     * @param string      $uri
     *
     * @return int|null
     */
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

    /**
     * @param Extractable $entity
     * @param string      $uri
     */
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

    /**
     * @param Extractable $entity
     * @param string      $uri
     */
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

    /**
     * @param string $uri
     */
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
     * @param \Closure|string $entityClass
     * @param string          $uri
     *
     * @return HalResource
     */
    public function getResource($entityClass, string $uri): HalResource
    {
        $request = new Request(
            'GET',
            $uri,
            $this->getDefaultHeaders()
        );
        $response = $this->send($request);
        $halDocument = HalDeserializer::deserializeDocument((string) $response->getBody());

        return HalDeserializer::deserializeResource($entityClass, $halDocument);
    }

    /**
     * @param Report $report
     *
     * @return array
     */
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
     * @param \Closure|string $entityClass
     * @param string          $rel
     * @param string          $uri
     *
     * @return HalResources
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
     * @param Extractable $entity
     *
     * @return string
     */
    private function encodeEntity(Extractable $entity): string
    {
        return json_encode($entity->extract());
    }

    /**
     * @param Request $request
     *
     * @return mixed|ResponseInterface
     */
    private function send(Request $request)
    {
        $options = [
            'base_uri' => self::BASE_URI,
            'http_errors' => false,
        ];

        return $this->client->send($request, $options);
    }
}
