<?php

declare(strict_types=1);

namespace HelpScout\Api\Conversations\Threads;

use HelpScout\Api\Conversations\Threads\Support\HasCustomer;
use HelpScout\Api\Customers\Customer;

class ChatThread extends Thread
{
    public const TYPE = 'chat';

    use HasCustomer;

    public static function resourceUrl(int $conversationId): string
    {
        return sprintf('/v2/conversations/%d/chats', $conversationId);
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

        if ($this->customer instanceof Customer) {
            $data['customer'] = [
                'id' => $this->getCustomer()->getId(),
            ];
        }

        return $data;
    }
}
