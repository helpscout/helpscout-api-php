<?php

declare(strict_types=1);

namespace HelpScout\Api\Http;

use GuzzleHttp\Client;
use HelpScout\Api\Http\Auth\Auth;
use HelpScout\Api\Http\Auth\ClientCredentials;
use HelpScout\Api\Http\Auth\LegacyCredentials;
use HelpScout\Api\Http\Auth\NullCredentials;
use HelpScout\Api\Http\Auth\RefreshCredentials;

class Authenticator
{
    public const TOKEN_URL = 'https://api.helpscout.net/v2/oauth2/token';
    public const TRANSITION_URL = 'https://transition.helpscout.net';
    public const CONTENT_TYPE = 'application/json;charset=UTF-8';

    /**
     * @var Client
     */
    private $client;

    /**
     * @var Auth
     */
    private $auth;

    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var string
     */
    private $refreshToken;

    /**
     * @var int
     */
    private $ttl;

    /**
     * @param Client $client
     * @param Auth   $auth
     */
    public function __construct(Client $client, Auth $auth = null)
    {
        $this->client = $client;
        $this->auth = $auth ?? new NullCredentials();
    }

    /**
     * @return array
     */
    public function getTokens(): array
    {
        return [
            'refresh_token' => $this->refreshToken,
            'token_type' => 'Bearer',
            'access_token' => $this->accessToken,
            'expires_in' => $this->ttl,
        ];
    }

    /**
     * @param string $accessToken
     *
     * @return Authenticator
     */
    public function setAccessToken(string $accessToken): Authenticator
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * @return string
     */
    public function accessToken(): ?string
    {
        return $this->accessToken;
    }

    /**
     * @param string $refreshToken
     *
     * @return Authenticator
     */
    public function setRefreshToken(string $refreshToken): Authenticator
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    /**
     * @return string
     */
    public function refreshToken(): ?string
    {
        return $this->refreshToken;
    }

    /**
     * @param Client $client
     *
     * @return Authenticator
     */
    public function setClient(Client $client): Authenticator
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return array
     */
    public function getAuthHeader(): array
    {
        if ($this->accessToken === null) {
            $this->fetchTokens();
        }

        return [
            'Authorization' => 'Bearer '.$this->accessToken,
        ];
    }

    /**
     * @return Auth
     */
    public function getAuthCredentials(): Auth
    {
        return $this->auth;
    }

    /**
     * @param string $appId
     * @param string $appSecret
     */
    public function useClientCredentials(string $appId, string $appSecret): void
    {
        $this->auth = new ClientCredentials($appId, $appSecret);
    }

    /**
     * The Legacy Token auth scheme is provided as a developer convenience
     * while transitioning from v1 to v2 of the API. On June 6, 2019, we will
     * sunset v1 of the API. At that time, this method will no longer function
     * and we will remove it from the SDK.
     *
     * @deprecated
     *
     * @param string $clientId
     * @param string $apiKey
     */
    public function useLegacyToken(string $clientId, string $apiKey): void
    {
        $this->auth = new LegacyCredentials($clientId, $apiKey);
    }

    /**
     * @param string $appId
     * @param string $appSecret
     * @param string $refreshToken
     */
    public function useRefreshToken(string $appId, string $appSecret, string $refreshToken): void
    {
        $this->auth = new RefreshCredentials($appId, $appSecret, $refreshToken);
    }

    /**
     * @param Auth $auth
     */
    public function setAuth(Auth $auth): void
    {
        $this->auth = $auth;
    }

    protected function fetchTokens(): void
    {
        switch ($this->auth->getType()) {
            case LegacyCredentials::TYPE:
                $this->convertLegacyToken();
                break;
            case ClientCredentials::TYPE:
            case RefreshCredentials::TYPE:
                $this->fetchAccessAndRefreshToken();
                break;
            default:
                throw new \InvalidArgumentException('Cannot fetch tokens without app credentials');
        }
    }

    public function fetchAccessAndRefreshToken(): self
    {
        $tokens = $this->requestAuthTokens(
            $this->auth->getPayload(),
            self::TOKEN_URL
        );

        $this->accessToken = $tokens['access_token'];
        $this->ttl = $tokens['expires_in'];
        $this->refreshToken = $tokens['refresh_token'] ?? null;

        return $this;
    }

    /**
     * This conversion helper is provided as a developer convenience while
     * transitioning from v1 to v2 of the API. On June 6, 2019, we will sunset
     * v1 of the API. At that time, this method will no longer function and we
     * will remove it from the SDK.
     *
     * @deprecated
     */
    public function convertLegacyToken(): void
    {
        $tokens = $this->requestAuthTokens(
            $this->auth->getPayload(),
            self::TRANSITION_URL
        );

        $this->accessToken = $tokens['accessToken'];
        $this->refreshToken = $tokens['refreshToken'];
        $this->ttl = $tokens['expiresIn'];
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
        ];
        $options = [
            'headers' => $headers,
            'json' => $payload,
        ];

        $response = $this->client->request(
            'POST',
            $url,
            $options
        );

        return json_decode((string) $response->getBody(), true);
    }
}
