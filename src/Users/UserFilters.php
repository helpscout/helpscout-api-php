<?php

declare(strict_types=1);

namespace HelpScout\Api\Users;

use HelpScout\Api\Assert\Assert;

class UserFilters
{
    /**
     * @var int
     */
    private $mailbox;

    /**
     * @var string
     */
    private $email;

    public function getParams(): array
    {
        $params = [
            'mailbox' => $this->mailbox,
            'email' => $this->email,
        ];

        // Filter out null values & empty strings
        return array_filter($params);
    }

    public function inMailbox(int $mailbox): UserFilters
    {
        Assert::greaterThan($mailbox, 0);

        $filters = clone $this;
        $filters->mailbox = $mailbox;

        return $filters;
    }

    public function byEmail(string $email): UserFilters
    {
        $filters = clone $this;
        $filters->email = $email;

        return $filters;
    }
}
