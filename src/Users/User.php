<?php

declare(strict_types=1);

namespace HelpScout\Api\Users;

use DateTime;
use HelpScout\Api\Assert\Assert;
use HelpScout\Api\Entity\Hydratable;
use HelpScout\Api\Support\HydratesData;

class User implements Hydratable
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
    private $firstName;

    /**
     * @var string|null
     */
    private $lastName;

    /**
     * @var string|null
     */
    private $email;

    /**
     * @var string|null
     */
    private $role;

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
    private $type;

    /**
     * @var string|null
     */
    private $mention;

    /**
     * @var string|null
     */
    private $initials;

    /**
     * @var string|null
     */
    private $jobTitle;

    /**
     * @var string|null
     */
    private $phone;

    /**
     * @var array|null
     */
    private $alternateEmails;

    public function hydrate(array $data, array $embedded = [])
    {
        if (isset($data['id'])) {
            $this->setId((int) $data['id']);
        }
        $this->setCreatedAt($this->transformDateTime($data['createdAt'] ?? null));
        $this->setUpdatedAt($this->transformDateTime($data['updatedAt'] ?? null));

        // When a User is supplied via the Conversation's "createdBy" field it doesn't use the "name" suffix
        if (isset($data['firstName'])) {
            $this->setFirstName($data['firstName']);
        } elseif (isset($data['first'])) {
            $this->setFirstName($data['first']);
        }

        // When a User is supplied via the Conversation's "createdBy" field it doesn't use the "name" suffix
        if (isset($data['lastName'])) {
            $this->setLastName($data['lastName']);
        } elseif (isset($data['last'])) {
            $this->setLastName($data['last']);
        }

        $this->setEmail($data['email'] ?? null);
        $this->setRole($data['role'] ?? null);
        $this->setTimezone($data['timezone'] ?? null);
        $this->setPhotoUrl($data['photoUrl'] ?? null);
        $this->setType($data['type'] ?? null);
        $this->setMention($data['mention'] ?? null);
        $this->setInitials($data['initials'] ?? null);
        $this->setJobTitle($data['jobTitle'] ?? null);
        $this->setPhone($data['phone'] ?? null);
        $this->setAlternateEmails($data['alternateEmails'] ?? null);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(?int $id): User
    {
        Assert::greaterThan($id, 0);

        $this->id = $id;

        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt = null): User
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt = null): User
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     */
    public function setFirstName($firstName): User
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     */
    public function setLastName($lastName): User
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     */
    public function setEmail($email): User
    {
        $this->email = $email;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * @param string|null $role
     */
    public function setRole($role): User
    {
        $this->role = $role;

        return $this;
    }

    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    /**
     * @param string|null $timezone
     */
    public function setTimezone($timezone): User
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
    public function setPhotoUrl($photoUrl): User
    {
        $this->photoUrl = $photoUrl;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     */
    public function setType($type): User
    {
        $this->type = $type;

        return $this;
    }

    public function getMention(): ?string
    {
        return $this->mention;
    }

    /**
     * @param string|null $mention
     */
    public function setMention($mention): User
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
    public function setInitials($initials): User
    {
        $this->initials = $initials;

        return $this;
    }

    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    /**
     * @param string|null $jobTitle
     */
    public function setJobTitle($jobTitle): User
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     */
    public function setPhone($phone): User
    {
        $this->phone = $phone;

        return $this;
    }

    public function getalternateEmails(): ?array
    {
        return $this->alternateEmails;
    }

    /**
     * @param array|null $alternateEmails
     */
    public function setAlternateEmails($alternateEmails): User
    {
        $this->alternateEmails = $alternateEmails;

        return $this;
    }
}
