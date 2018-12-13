<?php

declare(strict_types=1);

namespace HelpScout\Api\Tags;

use HelpScout\Api\Entity\Extractable;
use HelpScout\Api\Entity\Hydratable;
use HelpScout\Api\Support\HydratesData;

class Tag implements Extractable, Hydratable
{
    use HydratesData;

    /**
     * @var string|null
     */
    private $id;

    /**
     * @var string|null
     */
    private $color;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $slug;

    /**
     * @var \DateTime|null
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     */
    private $updatedAt;

    /**
     * @var int|null
     */
    private $ticketCount;

    public function hydrate(array $data, array $embedded = [])
    {
        $this->setId($data['id'] ?? null);
        $this->setColor($data['color'] ?? null);

        // API endpoints sometimes use "tag" and sometimes "name" so we'll hydrate assuming it could be either
        if (isset($data['name'])) {
            $this->setName($data['name'] ?? null);
        } elseif (isset($data['tag'])) {
            $this->setName($data['tag']);
        }

        if (isset($data['slug'])) {
            $this->setSlug($data['slug']);
        }

        $this->setTicketCount($data['ticketCount'] ?? null);
        $this->setCreatedAt($data['createdAt'] ?? null);
        $this->setUpdatedAt($data['updatedAt'] ?? null);
    }

    /**
     * {@inheritdoc}
     */
    public function extract(): array
    {
        $data = [
            'id' => $this->getId(),
            'color' => $this->getColor(),
            'name' => $this->getName(),
            'slug' => $this->getSlug(),
            'ticketCount' => $this->getTicketCount(),
        ];

        if ($this->getCreatedAt() !== null) {
            $data['createdAt'] = $this->getCreatedAt()->format(Extractable::DATETIME_FORMAT);
        }

        if ($this->getUpdatedAt() !== null) {
            $data['updatedAt'] = $this->getUpdatedAt()->format(Extractable::DATETIME_FORMAT);
        }

        return $data;
    }

    /**
     * @param string|null $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * @param string|null $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @param string|null $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @return null|\DateTime
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param null|string $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $this->transformDateTime($createdAt);
    }

    /**
     * @return null|\DateTime
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param null|string $updatedAt
     */
    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = $this->transformDateTime($updatedAt);
    }

    /**
     * @return int|null
     */
    public function getTicketCount(): ?int
    {
        return $this->ticketCount;
    }

    /**
     * @param int|null $ticketCount
     */
    public function setTicketCount(?int $ticketCount): void
    {
        $this->ticketCount = $ticketCount;
    }
}
