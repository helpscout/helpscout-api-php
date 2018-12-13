<?php

declare(strict_types=1);

namespace HelpScout\Api\Http;

use HelpScout\Api\Entity\Extractable;
use HelpScout\Api\Http\Hal\HalDeserializer;
use HelpScout\Api\Http\Hal\HalResource;
use HelpScout\Api\Http\Hal\HalResources;
use HelpScout\Api\Reports\Report;
use Http\Client\Common\HttpMethodsClient;

class RestClient
{
    public const CONTENT_TYPE = 'application/json;charset=UTF-8';
    public const TOKEN_URL = 'https://api.helpscout.net/v2/oauth2/token';
    public const TRANSITION_URL = 'https://transition.helpscout.net';

    /**
     * @var HttpMethodsClient
     */
    private $httpClient;

    /**
     * @param HttpMethodsClient $httpClient
     */
    public function __construct(HttpMethodsClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $appId
     * @param string $appSecret
     *
     * @return array
     */
    public function fetchAccessAndRefreshToken(string $appId, string $appSecret): array
    {
        $payload = [
            'grant_type' => 'client_credentials',
            'client_id' => $appId,
            'client_secret' => $appSecret,
        ];

        return $this->requestAuthTokens($payload, self::TOKEN_URL);
    }

    /**
     * @param string $appId
     * @param string $appSecret
     * @param string $refreshToken
     *
     * @return array
     */
    public function refreshTokens(string $appId, string $appSecret, string $refreshToken): array
    {
        $payload = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
            'client_id' => $appId,
            'client_secret' => $appSecret,
        ];

        return $this->requestAuthTokens($payload, self::TOKEN_URL);
    }

    /**
     * @param string $clientId
     * @param string $apiKey
     *
     * @return array
     */
    public function convertLegacyToken(string $clientId, string $apiKey): array
    {
        $payload = [
            'clientId' => $clientId,
            'apiKey' => $apiKey,
        ];

        $tokens = $this->requestAuthTokens($payload, self::TRANSITION_URL);

        return [
            'access_token' => $tokens['accessToken'],
            'refresh_token' => $tokens['refreshToken'],
            'expires_in' => $tokens['expiresIn'],
            'token_type' => 'bearer',
        ];
    }

    /**
     * @param array  $payload
     * @param string $url
     *
     * @return array
     */
    private function requestAuthTokens(array $payload, string $url): array
    {
        $headers = [
            'Content-Type' => self::CONTENT_TYPE,
            'X-Token-Request' => true,
        ];
        $response = $this->httpClient->post(
            $url,
            $headers,
            json_encode($payload)
        );

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * @param Extractable $entity
     * @param string      $uri
     *
     * @return int|null
     */
    public function createResource(Extractable $entity, string $uri): ?int
    {
        $response = $this->httpClient->post(
            $uri,
            ['Content-Type' => self::CONTENT_TYPE],
            $this->encodeEntity($entity)
        );

        return $response->hasHeader('Resource-ID')
            ? (int) \current($response->getHeader('Resource-ID'))
            : null;
    }

    /**
     * @param Extractable $entity
     * @param string      $uri
     */
    public function updateResource(Extractable $entity, string $uri)
    {
        $this->httpClient->put(
            $uri,
            ['Content-Type' => self::CONTENT_TYPE],
            $this->encodeEntity($entity)
        );
    }

    /**
     * @param Extractable $entity
     * @param string      $uri
     */
    public function patchResource(Extractable $entity, string $uri)
    {
        $this->httpClient->patch(
            $uri,
            ['Content-Type' => self::CONTENT_TYPE],
            $this->encodeEntity($entity)
        );
    }

    /**
     * @param string $uri
     */
    public function deleteResource(string $uri)
    {
        $this->httpClient->delete($uri);
    }

    /**
     * @param \Closure|string $entityClass
     * @param string          $uri
     *
     * @return HalResource
     */
    public function getResource($entityClass, string $uri): HalResource
    {
        $response = $this->httpClient->get($uri);
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
        $response = $this->httpClient->get($report->getUriPath());
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
        $response = $this->httpClient->get($uri);
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
}
