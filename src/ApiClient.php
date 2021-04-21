<?php

declare(strict_types=1);

namespace HelpScout\Api;

use HelpScout\Api\Chats\ChatsEndpoint;
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
use HelpScout\Api\Teams\TeamsEndpoint;
use HelpScout\Api\Users\UsersEndpoint;
use HelpScout\Api\Webhooks\WebhooksEndpoint;
use HelpScout\Api\Workflows\WorkflowsEndpoint;
use Mockery\LegacyMockInterface;

class ApiClient
{
    public const CLIENT_VERSION = '3.2.0';

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
        'hs.teams' => TeamsEndpoint::class,
        'hs.chats' => ChatsEndpoint::class,
    ];

    /**
     * @var RestClient
     */
    private $restClient;

    /**
     * @var array
     */
    private $container = [];

    public function __construct(RestClient $restClient)
    {
        $this->restClient = $restClient;
    }

    public function mock(string $endpointName): LegacyMockInterface
    {
        $endpointName = 'hs.'.$endpointName;
        $mock = \Mockery::mock(self::AVAILABLE_ENDPOINTS[$endpointName]);

        $this->container[$endpointName] = $mock;

        return $mock;
    }

    public function clearMock(string $endpointName): void
    {
        $endpointName = 'hs.'.$endpointName;
        unset($this->container[$endpointName]);
    }

    public function clearContainer(): void
    {
        $this->container = [];
    }

    public function getAuthenticator(): Authenticator
    {
        return $this->restClient->getAuthenticator();
    }

    public function setAccessToken(string $accessToken): ApiClient
    {
        $this->getAuthenticator()
            ->setAccessToken($accessToken);

        return $this;
    }

    public function getTokens(): array
    {
        return $this->getAuthenticator()
            ->getTokens();
    }

    public function useClientCredentials(string $appId, string $appSecret): ApiClient
    {
        $this->getAuthenticator()
            ->useClientCredentials($appId, $appSecret);

        return $this;
    }

    public function useRefreshToken(string $appId, string $appSecret, string $refreshToken): ApiClient
    {
        $this->getAuthenticator()
            ->useRefreshToken($appId, $appSecret, $refreshToken);

        return $this;
    }

    /**
     * Takes an authorization code and exchanges it for an access/refresh token pair.
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

    public function workflows(): WorkflowsEndpoint
    {
        return $this->fetchFromContainer('hs.workflows');
    }

    public function webhooks(): WebhooksEndpoint
    {
        return $this->fetchFromContainer('hs.webhooks');
    }

    public function users(): UsersEndpoint
    {
        return $this->fetchFromContainer('hs.users');
    }

    public function tags(): TagsEndpoint
    {
        return $this->fetchFromContainer('hs.tags');
    }

    public function mailboxes(): MailboxesEndpoint
    {
        return $this->fetchFromContainer('hs.mailboxes');
    }

    public function customers(): CustomersEndpoint
    {
        return $this->fetchFromContainer('hs.customers');
    }

    public function customerEntry(): CustomerEntryEndpoint
    {
        return $this->fetchFromContainer('hs.customerEntry');
    }

    public function conversations(): ConversationsEndpoint
    {
        return $this->fetchFromContainer('hs.conversations');
    }

    public function chats(): ChatsEndpoint
    {
        return $this->fetchFromContainer('hs.chats');
    }

    public function teams(): TeamsEndpoint
    {
        return $this->fetchFromContainer('hs.teams');
    }

    public function threads(): ThreadsEndpoint
    {
        return $this->fetchFromContainer('hs.threads');
    }

    public function attachments(): AttachmentsEndpoint
    {
        return $this->fetchFromContainer('hs.attachments');
    }
}
