<?php

declare(strict_types=1);

namespace HelpScout\Api;

use HelpScout\Api\Conversations\ConversationsEndpoint;
use HelpScout\Api\Conversations\Threads\Attachments\AttachmentsEndpoint;
use HelpScout\Api\Conversations\Threads\ThreadsEndpoint;
use HelpScout\Api\Customers\CustomersEndpoint;
use HelpScout\Api\Customers\Entry\CustomerEntryEndpoint;
use HelpScout\Api\Http\Auth\CodeCredentials;
use HelpScout\Api\Http\Authenticator;
use HelpScout\Api\Http\RestClient;
use HelpScout\Api\Mailboxes\MailboxesEndpoint;
use HelpScout\Api\Reports\Report;
use HelpScout\Api\Tags\TagsEndpoint;
use HelpScout\Api\Users\UsersEndpoint;
use HelpScout\Api\Webhooks\WebhooksEndpoint;
use HelpScout\Api\Workflows\WorkflowsEndpoint;

class ApiClient
{
    public const CLIENT_VERSION = '2.2.4';

    public const AVAILABLE_ENDPOINTS = [
        'hs.workflows' => WorkflowsEndpoint::class,
        'hs.webhooks' => WebhooksEndpoint::class,
        'hs.users' => UsersEndpoint::class,
        'hs.threads' => ThreadsEndpoint::class,
        'hs.tags' => TagsEndpoint::class,
        'hs.mailboxes' => MailboxesEndpoint::class,
        'hs.customers' => CustomersEndpoint::class,
        'hs.customerEntry' => CustomerEntryEndpoint::class,
        'hs.conversations' => ConversationsEndpoint::class,
        'hs.attachments' => AttachmentsEndpoint::class,
    ];

    /**
     * @var RestClient
     */
    private $restClient;

    /**
     * @var array
     */
    private $container = [];

    /**
     * @param RestClient $restClient
     */
    public function __construct(RestClient $restClient)
    {
        $this->restClient = $restClient;
    }

    /**
     * @param string $endpointName
     *
     * @return \Mockery\MockInterface
     */
    public function mock(string $endpointName): \Mockery\MockInterface
    {
        $endpointName = 'hs.'.$endpointName;
        $mock = \Mockery::mock(self::AVAILABLE_ENDPOINTS[$endpointName]);

        $this->container[$endpointName] = $mock;

        return $mock;
    }

    /**
     * @param string $endpointName
     */
    public function clearMock(string $endpointName): void
    {
        $endpointName = 'hs.'.$endpointName;
        unset($this->container[$endpointName]);
    }

    public function clearContainer(): void
    {
        $this->container = [];
    }

    /**
     * @return Authenticator
     */
    public function getAuthenticator(): Authenticator
    {
        return $this->restClient->getAuthenticator();
    }

    /**
     * @param string $accessToken
     *
     * @return ApiClient
     */
    public function setAccessToken(string $accessToken): ApiClient
    {
        $this->getAuthenticator()
            ->setAccessToken($accessToken);

        return $this;
    }

    /**
     * @return array
     */
    public function getTokens(): array
    {
        return $this->getAuthenticator()
            ->getTokens();
    }

    /**
     * @param string $appId
     * @param string $appSecret
     *
     * @return ApiClient
     */
    public function useClientCredentials(string $appId, string $appSecret): ApiClient
    {
        $this->getAuthenticator()
            ->useClientCredentials($appId, $appSecret);

        return $this;
    }

    /**
     * The Legacy Token auth scheme is provided as a developer convenience
     * while transitioning from v1 to v2 of the API. On June 6, 2019, we will
     * sunset v1 of the API. At that time, this method will no longer function
     * and we will remove it from the SDK.
     *
     * @param string $clientId
     * @param string $apiKey
     *
     * @return ApiClient
     *
     * @deprecated
     */
    public function useLegacyToken(string $clientId, string $apiKey): ApiClient
    {
        $this->getAuthenticator()
            ->useLegacyToken($clientId, $apiKey);

        return $this;
    }

    /**
     * @param string $appId
     * @param string $appSecret
     * @param string $refreshToken
     *
     * @return ApiClient
     */
    public function useRefreshToken(string $appId, string $appSecret, string $refreshToken): ApiClient
    {
        $this->getAuthenticator()
            ->useRefreshToken($appId, $appSecret, $refreshToken);

        return $this;
    }

    /**
     * Takes an authorization code and exchanges it for an access/refresh token pair.
     *
     * @param string $appId
     * @param string $appSecret
     * @param string $authorizationCode
     *
     * @return ApiClient
     */
    public function swapAuthorizationCodeForReusableTokens(
        string $appId,
        string $appSecret,
        string $authorizationCode
    ): ApiClient {
        $authenticator = $this->getAuthenticator();

        $authenticator->setAuth(new CodeCredentials($appId, $appSecret, $authorizationCode));
        $authenticator->fetchAccessAndRefreshToken();

        $this->useRefreshToken($appId, $appSecret, $authenticator->refreshToken());

        return $this;
    }

    /**
     * @param string $reportName
     * @param array  $params
     *
     * @return array
     */
    public function runReport(string $reportName, array $params = []): array
    {
        if (!\class_exists($reportName)) {
            throw new \InvalidArgumentException("'{$reportName}' is not a valid report");
        }

        /** @var Report $report */
        $report = $reportName::getInstance($params);

        return $this->restClient->getReport($report);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    protected function fetchFromContainer(string $key)
    {
        if (isset($this->container[$key])) {
            return $this->container[$key];
        } else {
            $class = self::AVAILABLE_ENDPOINTS[$key];
            $endpoint = new $class($this->restClient);
            $this->container[$key] = $endpoint;

            return $endpoint;
        }
    }

    /**
     * @return WorkflowsEndpoint
     */
    public function workflows(): WorkflowsEndpoint
    {
        return $this->fetchFromContainer('hs.workflows');
    }

    /**
     * @return WebhooksEndpoint
     */
    public function webhooks(): WebhooksEndpoint
    {
        return $this->fetchFromContainer('hs.webhooks');
    }

    /**
     * @return UsersEndpoint
     */
    public function users(): UsersEndpoint
    {
        return $this->fetchFromContainer('hs.users');
    }

    /**
     * @return TagsEndpoint
     */
    public function tags(): TagsEndpoint
    {
        return $this->fetchFromContainer('hs.tags');
    }

    /**
     * @return MailboxesEndpoint
     */
    public function mailboxes(): MailboxesEndpoint
    {
        return $this->fetchFromContainer('hs.mailboxes');
    }

    /**
     * @return CustomersEndpoint
     */
    public function customers(): CustomersEndpoint
    {
        return $this->fetchFromContainer('hs.customers');
    }

    /**
     * @return CustomerEntryEndpoint
     */
    public function customerEntry(): CustomerEntryEndpoint
    {
        return $this->fetchFromContainer('hs.customerEntry');
    }

    /**
     * @return ConversationsEndpoint
     */
    public function conversations(): ConversationsEndpoint
    {
        return $this->fetchFromContainer('hs.conversations');
    }

    /**
     * @return ThreadsEndpoint
     */
    public function threads(): ThreadsEndpoint
    {
        return $this->fetchFromContainer('hs.threads');
    }

    /**
     * @return AttachmentsEndpoint
     */
    public function attachments(): AttachmentsEndpoint
    {
        return $this->fetchFromContainer('hs.attachments');
    }
}
