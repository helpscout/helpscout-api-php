<?php

declare(strict_types=1);

namespace HelpScout\Api\Conversations\Threads\Support;

use HelpScout\Api\Users\User;

trait HasUser
{
    /**
     * @var int|null
     */
    private $userId;

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function setUser(User $user): self
    {
        $this->userId = $user->getId();

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }
}
