<?php

declare(strict_types=1);

namespace HelpScout\Api\Conversations\Threads;

use HelpScout\Api\Support\HasCustomer;

class PhoneThread extends Thread
{
    use HasCustomer;

    public const TYPE = 'phone';

    public static function resourceUrl(int $conversationId): string
    {
        return sprintf('/v2/conversations/%d/phones', $conversationId);
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
