<?php

declare(strict_types=1);

namespace HelpScout\Api\Conversations;

use HelpScout\Api\Assert\Assert;

class ConversationRequest
{
    /**
     * @var array
     */
    private $links = [];

    public function __construct(array $links = [])
    {
        foreach ($links as $link) {
            $this->addLink($link);
        }
    }

    public function getLinks(): array
    {
        return $this->links;
    }

    private function addLink(string $link)
    {
        Assert::oneOf($link, [
            ConversationLinks::MAILBOX,
            ConversationLinks::PRIMARY_CUSTOMER,
            ConversationLinks::CREATED_BY_CUSTOMER,
            ConversationLinks::CREATED_BY_USER,
            ConversationLinks::CLOSED_BY,
            ConversationLinks::THREADS,
            ConversationLinks::ASSIGNEE,
            ConversationLinks::WEB,
        ]);

        $this->links[] = $link;
    }

    public function hasLink(string $rel): bool
    {
        return in_array($rel, $this->links, true);
    }

    public function withMailbox(): self
    {
        return $this->with(ConversationLinks::MAILBOX);
    }

    public function withPrimaryCustomer(): self
    {
        return $this->with(ConversationLinks::PRIMARY_CUSTOMER);
    }

    public function withCreatedByCustomer(): self
    {
        return $this->with(ConversationLinks::CREATED_BY_CUSTOMER);
    }

    public function withCreatedByUser(): self
    {
        return $this->with(ConversationLinks::CREATED_BY_USER);
    }

    public function withClosedBy(): self
    {
        return $this->with(ConversationLinks::CLOSED_BY);
    }

    public function withThreads(): self
    {
        return $this->with(ConversationLinks::THREADS);
    }

    public function withAssignee(): self
    {
        return $this->with(ConversationLinks::ASSIGNEE);
    }

    public function withWeb(): self
    {
        return $this->with(ConversationLinks::WEB);
    }

    private function with(string $link): self
    {
        $request = clone $this;
        $request->addLink($link);

        return $request;
    }
}
