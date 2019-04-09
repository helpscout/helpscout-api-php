<?php

declare(strict_types=1);

namespace HelpScout\Api\Tests\Webhooks;

use HelpScout\Api\Webhooks\Webhook;
use PHPUnit\Framework\TestCase;

class WebhookTest extends TestCase
{
    public function testHydrateAndExtract()
    {
        $data = [
            'id' => 123,
            'url' => 'http://bad-url.com',
            'state' => 'disabled',
            'events' => ['convo.assigned', 'convo.moved'],
            'secret' => 'mZ9XbGHodX',
        ];

        $webhook = new Webhook();
        $webhook->hydrate($data);

        $this->assertSame(123, $webhook->getId());
        $this->assertSame('disabled', $webhook->getState());
        $this->assertSame('http://bad-url.com', $webhook->getUrl());
        $this->assertSame(['convo.assigned', 'convo.moved'], $webhook->getEvents());
        $this->assertSame('mZ9XbGHodX', $webhook->getSecret());

        $this->assertSame($data, $webhook->extract());
    }
}
