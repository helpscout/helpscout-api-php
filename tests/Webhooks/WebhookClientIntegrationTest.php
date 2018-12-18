<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Webhooks;

use HelpScout\Api\Tests\ApiClientIntegrationTestCase;
use HelpScout\Api\Tests\Payloads\WebhookPayloads;
use HelpScout\Api\Webhooks\Webhook;

/**
 * @group integration
 */
class WebhookClientIntegrationTest extends ApiClientIntegrationTestCase
{
    public function testCreateWebhook()
    {
        $id = 123;
        $headers = [
            'Resource-ID' => $id,
        ];
        $this->stubResponse(
            $this->getResponse(201, '', $headers)
        );

        $data = [
            'url' => 'http://bad-url.com',
            'state' => 'disabled',
            'events' => ['convo.assigned', 'convo.moved'],
            'secret' => 'mZ9XbGHodX',
        ];
        $webhook = new Webhook();
        $webhook->hydrate($data);

        $insertId = $this->client->webhooks()->create($webhook);
        $this->assertSame($id, $insertId);

        $this->verifyRequestWithData(
            'https://api.helpscout.net/v2/webhooks',
            'POST',
            [
                'id' => null,
                'url' => 'http://bad-url.com',
                'state' => 'disabled',
                'events' => ['convo.assigned', 'convo.moved'],
                'secret' => 'mZ9XbGHodX',
            ]
        );
    }

    public function testUpdateWebhook()
    {
        $this->stubResponse($this->getResponse(204));

        $data = [
            'id' => 123,
            'url' => 'http://bad-url.com',
            'state' => 'disabled',
            'events' => ['convo.assigned', 'convo.moved'],
            'secret' => 'mZ9XbGHodX',
        ];

        $newUrl = 'http://bad-url.com/really_really_bad';

        $webhook = new Webhook();
        $webhook->hydrate($data);

        $webhook->setUrl($newUrl);

        $this->client->webhooks()->update($webhook);

        $this->verifyRequestWithData(
            'https://api.helpscout.net/v2/webhooks/123',
            'PUT',
            [
                'id' => 123,
                'url' => $newUrl,
                'state' => 'disabled',
                'events' => ['convo.assigned', 'convo.moved'],
                'secret' => 'mZ9XbGHodX',
            ]
        );
    }

    public function testGetWebhook()
    {
        $this->stubResponse(
            $this->getResponse(200, WebhookPayloads::getWebhook(123))
        );

        $webhook = $this->client->webhooks()->get(123);

        $this->assertInstanceOf(Webhook::class, $webhook);
        $this->assertSame(123, $webhook->getId());

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/webhooks/123'
        );
    }

    public function testGetWebhooks()
    {
        $this->stubResponse(
            $this->getResponse(200, WebhookPayloads::getWebhooks(1, 10))
        );

        $webhooks = $this->client->webhooks()->list();

        $this->assertCount(10, $webhooks);
        $this->assertInstanceOf(Webhook::class, $webhooks[0]);

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/webhooks'
        );
    }

    public function testListWebhooksWithEmptyCollection()
    {
        $this->stubResponse(
            $this->getResponse(200, WebhookPayloads::getWebhooks(1, 0))
        );

        $webhooks = $this->client->webhooks()->list();

        $this->assertCount(0, $webhooks);

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/webhooks'
        );
    }

    public function testListWebhooksParsesPageMetadata()
    {
        $this->stubResponse(
            $this->getResponse(200, WebhookPayloads::getWebhooks(3, 35))
        );

        $webhooks = $this->client->webhooks()->list();

        $this->assertSame(3, $webhooks->getPageNumber());
        $this->assertSame(10, $webhooks->getPageSize());
        $this->assertSame(10, $webhooks->getPageElementCount());
        $this->assertSame(35, $webhooks->getTotalElementCount());
        $this->assertSame(4, $webhooks->getTotalPageCount());
    }

    public function testListWebhooksLazyLoadsPages()
    {
        $totalElements = 20;
        $this->stubResponses([
            $this->getResponse(200, WebhookPayloads::getWebhooks(1, $totalElements)),
            $this->getResponse(200, WebhookPayloads::getWebhooks(2, $totalElements)),
        ]);

        $webhooks = $this->client->webhooks()->list()->getPage(2);

        $this->assertCount(10, $webhooks);
        $this->assertInstanceOf(Webhook::class, $webhooks[0]);

        $this->verifyMultipleRequests([
            ['GET', 'https://api.helpscout.net/v2/webhooks'],
            ['GET', 'https://api.helpscout.net/v2/webhooks?page=2'],
        ]);
    }

    public function testDeleteWebhook()
    {
        $this->stubResponse($this->getResponse(204));
        $this->client->webhooks()->delete(1);

        $this->verifySingleRequest(
            'https://api.helpscout.net/v2/webhooks/1',
            'DELETE'
        );
    }
}
