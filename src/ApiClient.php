<?php

declare(strict_types=1);

namespace HelpScout\Api;

use HelpScout\Api\Conversations\ConversationsEndpoint;
use HelpScout\Api\Conversations\Threads\Attachments\AttachmentsEndpoint;
use HelpScout\Api\Conversations\Threads\ThreadsEndpoint;
use HelpScout\Api\Customers\CustomersEndpoint;
use HelpScout\Api\Customers\Entry\CustomerEntryEndpoint;
use HelpScout\Api\Http\Authenticator;
use HelpScout\Api\Http\History;
use HelpScout\Api\Http\RestClient;
use HelpScout\Api\Mailboxes\MailboxesEndpoint;
use HelpScout\Api\Reports\Report;
use HelpScout\Api\Tags\TagsEndpoint;
use HelpScout\Api\Users\UsersEndpoint;
use HelpScout\Api\Webhooks\WebhooksEndpoint;
use HelpScout\Api\Workflows\WorkflowsEndpoint;
use Psr\Http\Message\ResponseInterface;

class ApiClient
{
    public const AVAILABLE_ENDPOINTS = [
        'workflows' => WorkflowsEndpoint::class,
        'webhooks' => WebhooksEndpoint::class,
        'users' => UsersEndpoint::class,
        'tags' => TagsEndpoint::class,
        'mailboxes' => MailboxesEndpoint::class,
        'customers' => CustomersEndpoint::class,
        'customerEntry' => CustomerEntryEndpoint::class,
        'conversations' => ConversationsEndpoint::class,
        'attachments' => AttachmentsEndpoint::class,
    ];

    /**
     * @var RestClient
     */
    private $restClient;

    /**
     * @var Authenticator
     */
    private $authenticator;

    /**
     * @var History
     */
    private $history;

    /**
     * @var array
     */
    private $tokens = [];

    /**
     * @param RestClient    $restClient
     * @param Authenticator $authenticator
     * @param History       $history
     */
    public function __construct(RestClient $restClient, Authenticator $authenticator, History $history)
    {
        $this->restClient = $restClient;
        $this->authenticator = $authenticator;
        $this->history = $history;
    }

    /**
     * @param string $accessToken
     *
     * @return ApiClient
     */
    public function setAccessToken(string $accessToken): ApiClient
    {
        $this->authenticator->setAccessToken($accessToken);

        return $this;
    }

    /**
     * @return array
     */
    public function getTokens(): array
    {
        return $this->tokens;
    }

    /**
     * @param string $appId
     * @param string $appSecret
     *
     * @return ApiClient
     */
    public function useClientCredentials(string $appId, string $appSecret): ApiClient
    {
        $token = $this->fetchAccessToken($appId, $appSecret);

        return $this->setAccessToken($token);
    }

    /**
     * @param string $clientId
     * @param string $apiKey
     *
     * @return ApiClient
     */
    public function useLegacyToken(string $clientId, string $apiKey): ApiClient
    {
        $token = $this->convertLegacyToken($clientId, $apiKey);

        return $this->setAccessToken($token);
    }

    /**
     * @param string $appId
     * @param string $appSecret
     *
     * @return ApiClient
     */
    public function refreshAccessToken(string $appId, string $appSecret): ApiClient
    {
        $refreshToken = $this->tokens['refresh_token'] ?? '';
        if ($refreshToken) {
            $token = $this->getRefreshedAccessToken($appId, $appSecret, $refreshToken);
        } else {
            $token = $this->fetchAccessToken($appId, $appSecret);
        }

        return $this->setAccessToken($token);
    }

    /**
     * @param string $appId
     * @param string $appSecret
     *
     * @return string
     */
    private function fetchAccessToken(string $appId, string $appSecret): string
    {
        $this->tokens = $this->restClient->fetchAccessAndRefreshToken($appId, $appSecret);

        return $this->tokens['access_token'];
    }

    /**
     * @param string $clientId
     * @param string $apiKey
     *
     * @return string
     */
    private function convertLegacyToken(string $clientId, string $apiKey): string
    {
        $this->tokens = $this->restClient->convertLegacyToken($clientId, $apiKey);

        return $this->tokens['access_token'];
    }

    /**
     * @param string $appId
     * @param string $appSecret
     * @param string $refreshToken
     *
     * @return string
     */
    private function getRefreshedAccessToken(string $appId, string $appSecret, string $refreshToken): string
    {
        $this->tokens = $this->restClient->refreshTokens($appId, $appSecret, $refreshToken);

        return $this->tokens['access_token'];
    }

    /**
     * @return ResponseInterface|null
     */
    public function getLastResponse(): ?ResponseInterface
    {
        return $this->history->getLastResponse();
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
     * @return WorkflowsEndpoint
     */
    public function workflows(): WorkflowsEndpoint
    {
        return new WorkflowsEndpoint($this->restClient);
    }

    /**
     * @return WebhooksEndpoint
     */
    public function webhooks(): WebhooksEndpoint
    {
        return new WebhooksEndpoint($this->restClient);
    }

    /**
     * @return UsersEndpoint
     */
    public function users(): UsersEndpoint
    {
        return new UsersEndpoint($this->restClient);
    }

    /**
     * @return TagsEndpoint
     */
    public function tags(): TagsEndpoint
    {
        return new TagsEndpoint($this->restClient);
    }

    /**
     * @return MailboxesEndpoint
     */
    public function mailboxes(): MailboxesEndpoint
    {
        return new MailboxesEndpoint($this->restClient);
    }

    /**
     * @return CustomersEndpoint
     */
    public function customers(): CustomersEndpoint
    {
        return new CustomersEndpoint($this->restClient);
    }

    /**
     * @return CustomerEntryEndpoint
     */
    public function customerEntry(): CustomerEntryEndpoint
    {
        return new CustomerEntryEndpoint($this->restClient);
    }

    /**
     * @return ConversationsEndpoint
     */
    public function conversations(): ConversationsEndpoint
    {
        return new ConversationsEndpoint($this->restClient);
    }

    /**
     * @return ThreadsEndpoint
     */
    public function threads(): ThreadsEndpoint
    {
        return new ThreadsEndpoint($this->restClient);
    }

    /**
     * @return AttachmentsEndpoint
     */
    public function attachments(): AttachmentsEndpoint
    {
        return new AttachmentsEndpoint($this->restClient);
    }
}
