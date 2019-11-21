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

    /**
     * @return self
     */
    public function withMailbox(int $mailbox)
    {
        Assert::greaterThan($mailbox, 0);

        $filters = clone $this;
        $filters->mailbox = $mailbox;

        return $filters;
    }

    /**
     * @param string $status
     *
     * @return self
     */
    public function withEmail(string $email)
    {
        $filters = clone $this;
        $filters->email = $email;

        return $filters;
    }
}
