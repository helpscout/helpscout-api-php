<?php

declare(strict_types=1);

namespace HelpScout\Api\Mailboxes;

use HelpScout\Api\Endpoint;
use HelpScout\Api\Entity\PagedCollection;
use HelpScout\Api\Http\Hal\HalPagedResources;
use HelpScout\Api\Http\Hal\HalResource;

class MailboxesEndpoint extends Endpoint
{
    public const GET_MAILBOX_URI = '/v2/mailboxes/%d';
    public const LIST_MAILBOXES_URI = '/v2/mailboxes';
    public const RESOURCE_KEY = 'mailboxes';

    public function get(int $id, MailboxRequest $mailboxRequest = null): Mailbox
    {
        $mailboxResource = $this->restClient->getResource(
            Mailbox::class,
            sprintf(self::GET_MAILBOX_URI, $id));

        return $this->hydrateMailboxWithSubEntities(
            $mailboxResource,
            $mailboxRequest ?: new MailboxRequest()
        );
    }

    /**
     * @param MailboxRequest|null $mailboxRequest
     *
     * @return Mailbox[]|PagedCollection
     */
    public function list(MailboxRequest $mailboxRequest = null): PagedCollection
    {
        return $this->loadMailboxes(
            self::LIST_MAILBOXES_URI,
            $mailboxRequest ?: new MailboxRequest()
        );
    }

    /**
     * @param string         $uri
     * @param MailboxRequest $mailboxRequest
     *
     * @return Mailbox[]|PagedCollection
     */
    private function loadMailboxes(string $uri, MailboxRequest $mailboxRequest): PagedCollection
    {
        /** @var HalPagedResources */
        $mailboxResources = $this->restClient->getResources(Mailbox::class, 'mailboxes', $uri);
        $mailboxes = $mailboxResources->map(function (HalResource $mailboxResource) use ($mailboxRequest) {
            return $this->hydrateMailboxWithSubEntities($mailboxResource, $mailboxRequest);
        });

        return new PagedCollection(
            $mailboxes,
            $mailboxResources->getPageMetadata(),
            $mailboxResources->getLinks(),
            function (string $uri) use ($mailboxRequest) {
                return $this->loadMailboxes($uri, $mailboxRequest);
            }
        );
    }

    /**
     * @param HalResource    $mailboxResource
     * @param MailboxRequest $mailboxRequest
     *
     * @return Mailbox
     */
    private function hydrateMailboxWithSubEntities(
        HalResource $mailboxResource,
        MailboxRequest $mailboxRequest
    ): Mailbox {
        $mailboxLoader = new MailboxLoader($this->restClient, $mailboxResource, $mailboxRequest->getLinks());
        $mailboxLoader->load();

        return $mailboxResource->getEntity();
    }
}
