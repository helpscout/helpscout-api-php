<?php

declare(strict_types=1);

namespace HelpScout\Api\Teams;

use DateTime;
use HelpScout\Api\Entity\Hydratable;
use HelpScout\Api\Support\HydratesData;

class Team implements Hydratable
{
    use HydratesData;

    /**
     * @var int
     */
    private $id;

    /**
     * @var DateTime
     */
    private $createdAt;

    /**
     * @var DateTime
     */
    private $updatedAt;

    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string|null
     */
    private $timezone;

    /**
     * @var string|null
     */
    private $photoUrl;

    /**
     * @var string|null
     */
    private $mention;

    /**
     * @var string|null
     */
    private $initials;

    public function hydrate(array $data, array $embedded = [])
    {
        if (isset($data['id'])) {
            $this->setId((int) $data['id']);
        }
        $this->setCreatedAt($this->transformDateTime($data['createdAt'] ?? null));
        $this->setUpdatedAt($this->transformDateTime($data['updatedAt'] ?? null));

        $this->setName($data['name'] ?? null);
        $this->setTimezone($data['timezone'] ?? null);
        $this->setPhotoUrl($data['photoUrl'] ?? null);
        $this->setMention($data['mention'] ?? null);
        $this->setInitials($data['initials'] ?? null);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(?int $id): Team
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName($name): Team
    {
        $this->name = $name;

        return $this;
    }

    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    /**
     * @param string|null $timezone
     */
    public function setTimezone($timezone): Team
    {
        $this->timezone = $timezone;

        return $this;
    }

    public function getPhotoUrl(): ?string
    {
        return $this->photoUrl;
    }

    /**
     * @param string|null $photoUrl
     */
    public function setPhotoUrl($photoUrl): Team
    {
        $this->photoUrl = $photoUrl;

        return $this;
    }

    public function getMention(): ?string
    {
        return $this->mention;
    }

    /**
     * @param string|null $mention
     */
    public function setMention($mention): Team
    {
        $this->mention = $mention;

        return $this;
    }

    public function getInitials(): ?string
    {
        return $this->initials;
    }

    /**
     * @param string|null $initials
     */
    public function setInitials($initials): Team
    {
        $this->initials = $initials;

        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt = null): Team
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt = null): Team
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
