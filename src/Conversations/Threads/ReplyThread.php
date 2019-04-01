<?php

declare(strict_types=1);

namespace HelpScout\Api\Conversations\Threads;

use HelpScout\Api\Conversations\Threads\Support\HasCustomer;
use HelpScout\Api\Conversations\Threads\Support\HasPartiesToBeNotified;
use HelpScout\Api\Conversations\Threads\Support\HasUser;

class ReplyThread extends Thread
{
    public const TYPE = 'reply';

    use HasUser,
        HasCustomer,
        HasPartiesToBeNotified;

    public static function resourceUrl(int $conversationId): string
    {
        return sprintf('/v2/conversations/%d/reply', $conversationId);
    }

    /**
     * @var bool
     */
    private $draft = false;

    public function asDraft()
    {
        $this->draft = true;
    }

    public function notAsDraft()
    {
        $this->draft = false;
    }

    public function isDraft(): bool
    {
        return $this->draft;
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
        $data['draft'] = $this->isDraft();

        if ($this->hasCustomer()) {
            $data['customer'] = $this->getCustomer()->extract();
        }

        // When creating threads "user" is expected to be numeric rather
        // than an object with an "id" property
        if ($this->userId > 0) {
            $data['user'] = $this->userId;
        }

        return $data;
    }
}
