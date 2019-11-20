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
            MailboxLinks::FIELDS,
            MailboxLinks::FOLDERS,
        ]);

        $this->links[] = $link;
    }

    public function hasLink(string $rel): bool
    {
        return in_array($rel, $this->links, true);
    }

    public function withFields(): self
    {
        return $this->with(MailboxLinks::FIELDS);
    }

    public function withFolders(): self
    {
        return $this->with(MailboxLinks::FOLDERS);
    }

    private function with(string $link): self
    {
        $request = clone $this;
        $request->addLink($link);

        return $request;
    }
}
