<?php

declare(strict_types=1);

namespace HelpScout\Api\Webhooks;

use HelpScout\Api\Assert\Assert;
use HelpScout\Api\Entity\Extractable;
use HelpScout\Api\Entity\Hydratable;
use HelpScout\Api\Support\HydratesData;

class Webhook implements Hydratable, Extractable
{
    use HydratesData;

    public const VALID_STATES = [
        'enabled',
        'disabled',
    ];

    public const VALID_EVENTS = [
        'convo.agent.reply.created',
        'convo.assigned',
        'convo.created',
        'convo.customer.reply.created',
        'convo.deleted',
        'convo.merged',
        'convo.moved',
        'convo.note.created',
        'convo.status',
        'convo.tags',
        'customer.created',
        'customer.updated',
        'satisfaction.ratings',
        'beacon.chat.created',
        'beacon.chat.customer.replied',
    ];

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $state;

    /**
     * @var string
     */
    private $url;

    /**
     * @var array
     */
    private $events = [];

    /**
     * @var string
     */
    private $secret;

    public function hydrate(array $data, array $embedded = []): void
    {
        $this->setId($data['id'] ?? null);
        $this->setState($data['state'] ?? null);
        $this->setEvents($data['events'] ?? null);
        $this->setUrl($data['url'] ?? null);
        $this->setSecret($data['secret'] ?? null);
    }

    public function extract(): array
    {
        return [
            'id' => $this->getId(),
            'url' => $this->getUrl(),
            'state' => $this->getState(),
            'events' => $this->getEvents(),
            'secret' => $this->getSecret(),
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Webhook
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): Webhook
    {
        if (null !== $state) {
            Assert::oneOf($state, self::VALID_STATES);

            $this->state = $state;
        }

        return $this;
    }

    public function getEvents(): array
    {
        return $this->events;
    }

    public function setEvents(array $events = []): Webhook
    {
        $validEvents = array_values(
            array_intersect(self::VALID_EVENTS, $events)
        );

        Assert::notEmpty($validEvents);

        $this->events = $validEvents;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(?string $url): Webhook
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getSecret(): ?string
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     */
    public function setSecret(?string $secret): Webhook
    {
        $this->secret = $secret;

        return $this;
    }
}
