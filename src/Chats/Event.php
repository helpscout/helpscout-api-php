<?php

declare(strict_types=1);

namespace HelpScout\Api\Chats;

use HelpScout\Api\Entity\Hydratable;
use HelpScout\Api\Support\HydratesData;
use HelpScout\Api\Users\User;

class Event implements Hydratable
{
    use HydratesData;

    /**
     * @var string|null
     */
    private $id;

    /**
     * @var string|null
     */
    private $type;

    /**
     * @var string|null
     */
    private $action;

    /**
     * @var User|null
     */
    private $author;

    /**
     * @var \DateTime|null
     */
    private $createdAt;

    /**
     * @var string|null
     */
    private $body;

    /**
     * @var array
     */
    private $params = [];

    public function hydrate(array $data, array $embedded = []): void
    {
        $this->id = $data['id'] ?? null;
        $this->type = $data['type'] ?? null;
        $this->action = $data['action'] ?? null;
        $this->createdAt = $this->transformDateTime($data['createdAt'] ?? null);
        $this->body = $data['body'] ?? null;
        $this->params = $data['params'] ?? [];

        if (isset($data['author'])) {
            /** @var User $author */
            $author = $this->hydrateOne(User::class, $data['author']);
            $this->author = $author;
        }
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function getParams(): array
    {
        return $this->params;
    }
}
