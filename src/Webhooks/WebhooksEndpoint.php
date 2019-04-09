<?php

declare(strict_types=1);

namespace HelpScout\Api\Webhooks;

use HelpScout\Api\Endpoint;
use HelpScout\Api\Entity\PagedCollection;

class WebhooksEndpoint extends Endpoint
{
    public const GET_WEBHOOK_URI = '/v2/webhooks/%d';
    public const LIST_WEBHOOKS_URI = '/v2/webhooks';
    public const CREATE_WEBHOOK_URI = '/v2/webhooks';
    public const UPDATE_WEBHOOK_URI = '/v2/webhooks/%d';
    public const DELETE_WEBHOOK_URI = '/v2/webhooks/%d';
    public const RESOURCE_KEY = 'webhooks';

    /**
     * @param Webhook $webhook
     *
     * @return int
     */
    public function create(Webhook $webhook): int
    {
        return $this->restClient->createResource(
            $webhook,
            self::CREATE_WEBHOOK_URI
        );
    }

    /**
     * @param int $id
     *
     * @return Webhook
     */
    public function get(int $id): Webhook
    {
        return $this->loadResource(
            Webhook::class,
            sprintf(self::GET_WEBHOOK_URI, $id)
        );
    }

    /**
     * @return Webhook[]|PagedCollection
     */
    public function list(): PagedCollection
    {
        return $this->loadPage(
            Webhook::class,
            self::RESOURCE_KEY,
            self::LIST_WEBHOOKS_URI
        );
    }

    /**
     * @param Webhook $webhook
     */
    public function update(Webhook $webhook): void
    {
        $this->restClient
            ->updateResource(
                $webhook,
                sprintf(self::UPDATE_WEBHOOK_URI, $webhook->getId())
            );
    }

    /**
     * @param int $webhookId
     */
    public function delete(int $webhookId): void
    {
        $this->restClient->deleteResource(
            sprintf(self::DELETE_WEBHOOK_URI, $webhookId)
        );
    }
}
