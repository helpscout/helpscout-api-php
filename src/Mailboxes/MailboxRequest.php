<?php

declare(strict_types=1);

namespace HelpScout\Api\Mailboxes;

use HelpScout\Api\Assert\Assert;

class MailboxRequest
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
            MailboxLinks::FIELDS,
            MailboxLinks::FOLDERS,
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
    public function withFields(): self
    {
        return $this->with(MailboxLinks::FIELDS);
    }

    /**
     * @return self
     */
    public function withFolders(): self
    {
        return $this->with(MailboxLinks::FOLDERS);
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
