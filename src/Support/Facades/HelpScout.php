<?php

declare(strict_types=1);

namespace HelpScout\Api\Support\Facades;

use HelpScout\Api\ApiClient;
use HelpScout\Api\Conversations\ConversationsEndpoint;
use HelpScout\Api\Conversations\Threads\Attachments\AttachmentsEndpoint;
use HelpScout\Api\Conversations\Threads\ThreadsEndpoint;
use HelpScout\Api\Customers\CustomersEndpoint;
use HelpScout\Api\Customers\Entry\CustomerEntryEndpoint;
use HelpScout\Api\Http\Authenticator;
use HelpScout\Api\Mailboxes\MailboxesEndpoint;
use HelpScout\Api\Tags\TagsEndpoint;
use HelpScout\Api\Users\UsersEndpoint;
use HelpScout\Api\Webhooks\WebhooksEndpoint;
use HelpScout\Api\Workflows\WorkflowsEndpoint;

/**
 * @codeCoverageIgnore
 *
 * @method static \Mockery\MockInterface mock(string $endpointName)
 * @method static void clearMock(string $endpointName)
 * @method static void clearContainer()
 * @method static Authenticator getAuthenticator()
 * @method static ApiClient setAccessToken(string $accessToken)
 * @method static array getTokens()
 * @method static ApiClient useClientCredentials(string $appId, string $appSecret)
 * @method static ApiClient useLegacyToken(string $clientId, string $apiKey)
 * @method static ApiClient useRefreshToken(string $appId, string $appSecret, string $refreshToken)
 * @method static array runReport(string $reportName, array $params = [])
 * @method static WorkflowsEndpoint workflows()
 * @method static WebhooksEndpoint webhooks()
 * @method static UsersEndpoint users()
 * @method static TagsEndpoint tags()
 * @method static MailboxesEndpoint mailboxes()
 * @method static CustomersEndpoint customers()
 * @method static CustomerEntryEndpoint customerEntry()
 * @method static ConversationsEndpoint conversations()
 * @method static ThreadsEndpoint threads()
 * @method static AttachmentsEndpoint attachments()
 */
class HelpScout extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'helpscout';
    }
}
