<?php

declare(strict_types=1);

namespace HelpScout\Api\Conversations;

use DateTime;
use HelpScout\Api\Entity\Extractable;
use HelpScout\Api\Entity\Hydratable;
use HelpScout\Api\Support\ExtractsData;
use HelpScout\Api\Support\HydratesData;

class CustomerWaitingSince implements Extractable, Hydratable
{
    use ExtractsData,
        HydratesData;

    /**
     * @var DateTime
     */
    private $time;

    /**
     * @var string|null
     */
    private $friendly;

    /**
     * @var string|null
     */
    private $latestReplyFrom;

    public function hydrate(array $data, array $embedded = [])
    {
        $this->setTime($this->transformDateTime($data['time'] ?? null));
        $this->setFriendly($data['friendly'] ?? null);
        $this->setLatestReplyFrom($data['latestReplyFrom'] ?? null);
    }

    /**
     * {@inheritdoc}
     */
    public function extract(): array
    {
        $fields = [
            'time' => null,
            'friendly' => $this->getFriendly(),
            'latestReplyFrom' => $this->getLatestReplyFrom(),
        ];

        if ($this->getTime() instanceof DateTime) {
            $fields['time'] = $this->to8601Utc($this->getTime());
        }

        return $fields;
    }

    public function setTime(DateTime $time = null): self
    {
        $this->time = $time;

        return $this;
    }

    public function getTime(): ?DateTime
    {
        return $this->time;
    }

    public function getFriendly(): ?string
    {
        return $this->friendly;
    }

    /**
     * @param string|null $friendly
     */
    public function setFriendly($friendly): self
    {
        $this->friendly = $friendly;

        return $this;
    }

    /**
     * @param string|null $latestReplyFrom
     */
    public function setLatestReplyFrom($latestReplyFrom): self
    {
        $this->latestReplyFrom = $latestReplyFrom;

        return $this;
    }

    public function getLatestReplyFrom(): ?string
    {
        return $this->latestReplyFrom;
    }
}
