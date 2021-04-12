<?php

declare(strict_types=1);

namespace HelpScout\Api\Conversations\Threads;

use HelpScout\Api\Conversations\Threads\Support\HasPartiesToBeNotified;
use HelpScout\Api\Support\HasCustomer;

class CustomerThread extends Thread
{
    use HasCustomer,
        HasPartiesToBeNotified;

    public const TYPE = 'customer';

    public static function resourceUrl(int $conversationId): string
    {
        return sprintf('/v2/conversations/%d/customer', $conversationId);
    }

    public function getType(): ?string
    {
        return self::TYPE;
    }

    public function hydrate(array $data, array $embedded = [])
    {
        parent::hydrate($data, $embedded);

        if (isset($data['customer']) && is_array($data['customer'])) {
            $this->hydrateCustomer($data['customer']);
        }
    }

    public function extract(): array
    {
        $data = parent::extract();
        $data['type'] = self::TYPE;

        if ($this->hasCustomer()) {
            $data['customer'] = $this->getCustomer()->extract();
        }

        return $data;
    }
}
