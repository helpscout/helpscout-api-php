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

    public function setUserId(int $userId)
    {
        $this->userId = $userId;
    }

    public function setUser(User $user)
    {
        $this->userId = $user->getId();
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }
}
