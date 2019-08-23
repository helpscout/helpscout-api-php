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

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     *
     * @return Team
     */
    public function setId(?int $id): Team
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     *
     * @return Team
     */
    public function setName($name): Team
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    /**
     * @param string|null $timezone
     *
     * @return Team
     */
    public function setTimezone($timezone): Team
    {
        $this->timezone = $timezone;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhotoUrl(): ?string
    {
        return $this->photoUrl;
    }

    /**
     * @param string|null $photoUrl
     *
     * @return Team
     */
    public function setPhotoUrl($photoUrl): Team
    {
        $this->photoUrl = $photoUrl;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMention(): ?string
    {
        return $this->mention;
    }

    /**
     * @param string|null $mention
     *
     * @return Team
     */
    public function setMention($mention): Team
    {
        $this->mention = $mention;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getInitials(): ?string
    {
        return $this->initials;
    }

    /**
     * @param string|null $initials
     *
     * @return Team
     */
    public function setInitials($initials): Team
    {
        $this->initials = $initials;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime|null $createdAt
     *
     * @return Team
     */
    public function setCreatedAt(DateTime $createdAt = null): Team
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime|null $updatedAt
     *
     * @return Team
     */
    public function setUpdatedAt(DateTime $updatedAt = null): Team
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
