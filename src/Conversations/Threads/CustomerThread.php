<?php

declare(strict_types=1);

namespace HelpScout\Api\Conversations\Threads;

use HelpScout\Api\Conversations\Threads\Support\HasCustomer;
use HelpScout\Api\Conversations\Threads\Support\HasPartiesToBeNotified;

class CustomerThread extends Thread
{
    public const TYPE = 'customer';

    use HasCustomer,
        HasPartiesToBeNotified;

    public static function resourceUrl(int $conversationId): string
    {
        return sprintf('/v2/conversations/%d/customer', $conversationId);
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
