<?php

declare(strict_types=1);

namespace HelpScout\Api\Conversations\Threads;

use HelpScout\Api\Conversations\Threads\Support\HasCustomer;
use HelpScout\Api\Customers\Customer;

class PhoneThread extends Thread
{
    public const TYPE = 'phone';

    use HasCustomer;

    public function hydrate(array $data, array $embedded = [])
    {
        parent::hydrate($data, $embedded);

        if (isset($data['customer']) && is_array($data['customer'])) {
            $this->hydrateCustomer($data['customer']);
        }
    }

    public static function resourceUrl(int $conversationId): string
    {
        return sprintf('/v2/conversations/%d/phones', $conversationId);
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
