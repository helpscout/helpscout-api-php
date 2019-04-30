<?php

declare(strict_types=1);

namespace HelpScout\Api\Tags;

use HelpScout\Api\Entity\Extractable;
use HelpScout\Api\Entity\Hydratable;
use HelpScout\Api\Support\ExtractsData;
use HelpScout\Api\Support\HydratesData;

class Tag implements Extractable, Hydratable
{
    use HydratesData,
        ExtractsData;

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
            $data['createdAt'] = $this->to8601Utc($this->getCreatedAt());
        }

        if ($this->getUpdatedAt() !== null) {
            $data['updatedAt'] = $this->to8601Utc($this->getUpdatedAt());
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
     *
     * @return Tag
     */
    public function setColor($color): Tag
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @param string|null $name
     *
     * @return Tag
     */
    public function setName($name): Tag
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $slug
     *
     * @return Tag
     */
    public function setSlug($slug): Tag
    {
        $this->slug = $slug;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param string|null $createdAt
     *
     * @return Tag
     */
    public function setCreatedAt($createdAt): Tag
    {
        $this->createdAt = $this->transformDateTime($createdAt);

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param string|null $updatedAt
     *
     * @return Tag
     */
    public function setUpdatedAt($updatedAt): Tag
    {
        $this->updatedAt = $this->transformDateTime($updatedAt);

        return $this;
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
     *
     * @return Tag
     */
    public function setTicketCount(?int $ticketCount): Tag
    {
        $this->ticketCount = $ticketCount;

        return $this;
    }
}
