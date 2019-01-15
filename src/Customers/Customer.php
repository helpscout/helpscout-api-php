<?php

declare(strict_types=1);

namespace HelpScout\Api\Customers;

use DateTime;
use HelpScout\Api\Assert\Assert;
use HelpScout\Api\Customers\Entry\Address;
use HelpScout\Api\Customers\Entry\Chat;
use HelpScout\Api\Customers\Entry\Email;
use HelpScout\Api\Customers\Entry\Phone;
use HelpScout\Api\Customers\Entry\SocialProfile;
use HelpScout\Api\Customers\Entry\Website;
use HelpScout\Api\Entity\Collection;
use HelpScout\Api\Entity\Extractable;
use HelpScout\Api\Entity\Hydratable;
use HelpScout\Api\Support\HydratesData;

class Customer implements Extractable, Hydratable
{
    use HydratesData;

    /**
     * @var int
     */
    private $id;

    /**
     * @var DateTime|null
     */
    private $createdAt;

    /**
     * @var DateTime|null
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
    private $gender;

    /**
     * @var string|null
     */
    private $jobTitle;

    /**
     * @var string|null
     */
    private $location;

    /**
     * @var string|null
     */
    private $organization;

    /**
     * @var string|null
     */
    private $photoType;

    /**
     * @var string|null
     */
    private $photoUrl;

    /**
     * @var string|null
     */
    private $background;

    /**
     * @var string|null
     */
    private $age;

    /**
     * @var Address|null
     */
    private $address;

    /**
     * @var Chat[]|Collection
     */
    private $chats;

    /**
     * @var Email[]|Collection
     */
    private $emails;

    /**
     * @var Phone[]|Collection
     */
    private $phones;

    /**
     * @var SocialProfile[]|Collection
     */
    private $socialProfiles;

    /**
     * @var Website[]|Collection
     */
    private $websites;

    public function __construct()
    {
        $this->chats = new Collection();
        $this->emails = new Collection();
        $this->phones = new Collection();
        $this->socialProfiles = new Collection();
        $this->websites = new Collection();
    }

    public function hydrate(array $data, array $embedded = [])
    {
        if (isset($data['id'])) {
            $this->setId((int) $data['id']);
        }
        $this->setCreatedAt($this->transformDateTime($data['createdAt'] ?? null));
        $this->setUpdatedAt($this->transformDateTime($data['updatedAt'] ?? null));

        // When a customer is supplied via the "createdBy" field it doesn't use the "name" suffix
        if (isset($data['firstName'])) {
            $this->setFirstName($data['firstName']);
        } elseif (isset($data['first'])) {
            $this->setFirstName($data['first']);
        }

        // When a customer is supplied via the "createdBy" field it doesn't use the "name" suffix
        if (isset($data['lastName'])) {
            $this->setLastName($data['lastName']);
        } elseif (isset($data['last'])) {
            $this->setLastName($data['last']);
        }

        $this->setGender($data['gender'] ?? null);
        $this->setJobTitle($data['jobTitle'] ?? null);
        $this->setLocation($data['location'] ?? null);
        $this->setOrganization($data['organization'] ?? null);
        $this->setPhotoType($data['photoType'] ?? null);
        $this->setPhotoUrl($data['photoUrl'] ?? null);
        $this->setBackground($data['background'] ?? null);
        $this->setAge($data['age'] ?? null);
    }

    /**
     * {@inheritdoc}
     */
    public function extract(): array
    {
        return [
            'firstName' => $this->getFirstName(),
            'lastName' => $this->getLastName(),
            'gender' => $this->getGender(),
            'jobTitle' => $this->getJobTitle(),
            'location' => $this->getLocation(),
            'organization' => $this->getOrganization(),
            'photoType' => $this->getPhotoType(),
            'photoUrl' => $this->getPhotoUrl(),
            'background' => $this->getBackground(),
            'age' => $this->getAge(),
        ];
    }

    /**
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        Assert::greaterThan($id, 0);

        $this->id = $id;
    }

    /**
     * @return DateTime|null
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime|null $createdAt
     */
    public function setCreatedAt(DateTime $createdAt = null)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTime|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTime|null $updatedAt
     */
    public function setUpdatedAt(DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return string|null
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string|null
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string|null $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return string|null
     */
    public function getJobTitle()
    {
        return $this->jobTitle;
    }

    /**
     * @param string|null $jobTitle
     */
    public function setJobTitle($jobTitle)
    {
        $this->jobTitle = $jobTitle;
    }

    /**
     * @return string|null
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string|null $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return string|null
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @param string|null $organization
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;
    }

    /**
     * @return string|null
     */
    public function getPhotoType()
    {
        return $this->photoType;
    }

    /**
     * @param string|null $photoType
     */
    public function setPhotoType($photoType)
    {
        $this->photoType = $photoType;
    }

    /**
     * @return string|null
     */
    public function getPhotoUrl()
    {
        return $this->photoUrl;
    }

    /**
     * @param string|null $photoUrl
     */
    public function setPhotoUrl($photoUrl)
    {
        $this->photoUrl = $photoUrl;
    }

    /**
     * @return string|null
     */
    public function getBackground()
    {
        return $this->background;
    }

    /**
     * @param string|null $background
     */
    public function setBackground($background)
    {
        $this->background = $background;
    }

    /**
     * @return string|null
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param string|null $age
     */
    public function setAge($age)
    {
        $this->age = $age;
    }

    /**
     * @return Address|null
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param Address|null $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return Chat[]|Collection
     */
    public function getChats(): Collection
    {
        return $this->chats;
    }

    /**
     * @param Chat[]|Collection $chats
     */
    public function setChats(Collection $chats)
    {
        $this->chats = $chats;
    }

    /**
     * @return Email[]|Collection
     */
    public function getEmails(): Collection
    {
        return $this->emails;
    }

    /**
     * @return null|string
     */
    public function getFirstEmail(): ?string
    {
        $emails = $this->emails->toArray();
        $email = array_shift($emails);

        return $email instanceof Email
            ? $email->getValue()
            : null;
    }

    /**
     * @param Email[]|Collection $emails
     */
    public function setEmails(Collection $emails)
    {
        $emails =
        $this->emails = $emails;
    }

    /**
     * @return Phone[]|Collection
     */
    public function getPhones(): Collection
    {
        return $this->phones;
    }

    /**
     * @param Phone[]|Collection $phones
     */
    public function setPhones(Collection $phones)
    {
        $this->phones = $phones;
    }

    /**
     * @return SocialProfile[]|Collection
     */
    public function getSocialProfiles(): Collection
    {
        return $this->socialProfiles;
    }

    /**
     * @param SocialProfile[]|Collection $socialProfiles
     */
    public function setSocialProfiles(Collection $socialProfiles)
    {
        $this->socialProfiles = $socialProfiles;
    }

    /**
     * @return Website[]|Collection
     */
    public function getWebsites(): Collection
    {
        return $this->websites;
    }

    /**
     * @param Website[]|Collection $websites
     */
    public function setWebsites(Collection $websites)
    {
        $this->websites = $websites;
    }
}
