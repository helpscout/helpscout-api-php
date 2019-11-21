<?php

declare(strict_types=1);

namespace HelpScout\Api\Customers;

use HelpScout\Api\Assert\Assert;

/**
 * This class is deprecated now that all entities are always provided.
 *
 * @deprecated
 * @see https://developer.helpscout.com/mailbox-api/changelog/#2019-01-25-new-return-all-customer-entries-always
 */
class CustomerRequest
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
            CustomerLinks::ADDRESS,
            CustomerLinks::CHATS,
            CustomerLinks::EMAILS,
            CustomerLinks::PHONES,
            CustomerLinks::SOCIAL_PROFILES,
            CustomerLinks::WEBSITES,
        ]);

        $this->links[] = $link;
    }

    public function hasLink(string $rel): bool
    {
        return in_array($rel, $this->links, true);
    }

    public function withAddress(): self
    {
        return $this->with(CustomerLinks::ADDRESS);
    }

    public function withChats(): self
    {
        return $this->with(CustomerLinks::CHATS);
    }

    public function withEmails(): self
    {
        return $this->with(CustomerLinks::EMAILS);
    }

    public function withPhones(): self
    {
        return $this->with(CustomerLinks::PHONES);
    }

    public function withSocialProfiles(): self
    {
        return $this->with(CustomerLinks::SOCIAL_PROFILES);
    }

    public function withWebsites(): self
    {
        return $this->with(CustomerLinks::WEBSITES);
    }

    private function with(string $link): self
    {
        $request = clone $this;
        $request->addLink($link);

        return $request;
    }
}
