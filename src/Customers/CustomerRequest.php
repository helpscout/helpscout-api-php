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

    /**
     * @param array $links
     */
    public function __construct(array $links = [])
    {
        foreach ($links as $link) {
            $this->addLink($link);
        }
    }

    /**
     * @return array
     */
    public function getLinks(): array
    {
        return $this->links;
    }

    /**
     * @param string $link
     */
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

    /**
     * @param string $rel
     *
     * @return bool
     */
    public function hasLink(string $rel): bool
    {
        return in_array($rel, $this->links, true);
    }

    /**
     * @return self
     */
    public function withAddress(): self
    {
        return $this->with(CustomerLinks::ADDRESS);
    }

    /**
     * @return self
     */
    public function withChats(): self
    {
        return $this->with(CustomerLinks::CHATS);
    }

    /**
     * @return self
     */
    public function withEmails(): self
    {
        return $this->with(CustomerLinks::EMAILS);
    }

    /**
     * @return self
     */
    public function withPhones(): self
    {
        return $this->with(CustomerLinks::PHONES);
    }

    /**
     * @return self
     */
    public function withSocialProfiles(): self
    {
        return $this->with(CustomerLinks::SOCIAL_PROFILES);
    }

    /**
     * @return self
     */
    public function withWebsites(): self
    {
        return $this->with(CustomerLinks::WEBSITES);
    }

    /**
     * @param string $link
     *
     * @return self
     */
    private function with(string $link): self
    {
        $request = clone $this;
        $request->addLink($link);

        return $request;
    }
}
