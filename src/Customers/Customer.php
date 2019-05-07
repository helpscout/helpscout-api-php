<?php

declare(strict_types=1);

namespace HelpScout\Api\Customers;

use DateTime;
use HelpScout\Api\Assert\Assert;
use HelpScout\Api\Customers\Entry\Address;
use HelpScout\Api\Customers\Entry\ChatHandle;
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
     * @var ChatHandle[]|Collection
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

        // When a customer is supplied via the Conversation's "createdBy" field it doesn't use the "name" suffix
        if (isset($data['firstName'])) {
            $this->setFirstName($data['firstName']);
        } elseif (isset($data['first'])) {
            $this->setFirstName($data['first']);
        }

        // When a customer is supplied via the Conversation's "createdBy" field it doesn't use the "name" suffix
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

        if (isset($embedded['address']) && is_array($embedded['address'])) {
            /** @var Address $address */
            $address = $this->hydrateOne(Address::class, $embedded['address']);

            $this->setAddress($address);
        }

        if (isset($embedded['chats']) && is_array($embedded['chats'])) {
            $chats = $this->hydrateMany(ChatHandle::class, $embedded['chats']);

            $this->setChatHandles($chats);
        }

        if (isset($embedded['emails']) && is_array($embedded['emails'])) {
            $emails = $this->hydrateMany(Email::class, $embedded['emails']);

            $this->setEmails($emails);
        }

        if (isset($embedded['social_profiles']) && is_array($embedded['social_profiles'])) {
            $socialProfiles = $this->hydrateMany(SocialProfile::class, $embedded['social_profiles']);

            $this->setSocialProfiles($socialProfiles);
        }

        if (isset($embedded['phones']) && is_array($embedded['phones'])) {
            $phones = $this->hydrateMany(Phone::class, $embedded['phones']);

            $this->setPhones($phones);
        }

        if (isset($embedded['websites']) && is_array($embedded['websites'])) {
            $websites = $this->hydrateMany(Website::class, $embedded['websites']);

            $this->setWebsites($websites);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function extract(): array
    {
        // ensure no empty values are included in the extraction for cleaner debugging
        return array_filter([
            'id' => $this->getId(),
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

            // In some cases a single email is required for a Customer, so we have to include
            // the individual email separately, but the first email will always be a duplicate
            // of what's already in "emails"
            'email' => $this->getFirstEmail(),

            'address' => $this->getAddress() !== null ? $this->getAddress()->extract() : null,

            'emails' => $this->getEmails()->extract(),
            'phones' => $this->getPhones()->extract(),
            'chats' => $this->getChatHandles()->extract(),
            'socialProfiles' => $this->getSocialProfiles()->extract(),
            'websites' => $this->getWebsites()->extract(),
        ]);
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Customer
     */
    public function setId(int $id): Customer
    {
        Assert::greaterThan($id, 0);

        $this->id = $id;

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
     * @return Customer
     */
    public function setCreatedAt(DateTime $createdAt = null): Customer
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
     * @return Customer
     */
    public function setUpdatedAt(DateTime $updatedAt = null): Customer
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     *
     * @return Customer
     */
    public function setFirstName(?string $firstName): Customer
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     *
     * @return Customer
     */
    public function setLastName(?string $lastName): Customer
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getGender(): ?string
    {
        return $this->gender;
    }

    /**
     * @param string|null $gender
     *
     * @return Customer
     */
    public function setGender(?string $gender): Customer
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    /**
     * @param string|null $jobTitle
     *
     * @return Customer
     */
    public function setJobTitle(?string $jobTitle): Customer
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * @param string|null $location
     *
     * @return Customer
     */
    public function setLocation(?string $location): Customer
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOrganization(): ?string
    {
        return $this->organization;
    }

    /**
     * @param string|null $organization
     *
     * @return Customer
     */
    public function setOrganization(?string $organization): Customer
    {
        $this->organization = $organization;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhotoType(): ?string
    {
        return $this->photoType;
    }

    /**
     * @param string|null $photoType
     *
     * @return Customer
     */
    public function setPhotoType(?string $photoType): Customer
    {
        $this->photoType = $photoType;

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
     * @return Customer
     */
    public function setPhotoUrl(?string $photoUrl): Customer
    {
        $this->photoUrl = $photoUrl;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBackground(): ?string
    {
        return $this->background;
    }

    /**
     * @param string|null $background
     *
     * @return Customer
     */
    public function setBackground(?string $background): Customer
    {
        $this->background = $background;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAge(): ?string
    {
        return $this->age;
    }

    /**
     * @param string|null $age
     *
     * @return Customer
     */
    public function setAge(?string $age): Customer
    {
        $this->age = $age;

        return $this;
    }

    /**
     * @return Address|null
     */
    public function getAddress(): ?Address
    {
        return $this->address;
    }

    /**
     * @param Address|null $address
     *
     * @return Customer
     */
    public function setAddress(?Address $address): Customer
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return ChatHandle[]|Collection
     */
    public function getChatHandles(): Collection
    {
        return $this->chats;
    }

    /**
     * @param ChatHandle[]|Collection $chats
     *
     * @return Customer
     */
    public function setChatHandles(Collection $chats): Customer
    {
        $this->chats = $chats;

        return $this;
    }

    public function addChatHandle(ChatHandle $chat): Customer
    {
        $this->getChatHandles()->append($chat);

        return $this;
    }

    /**
     * @deprecated
     *
     * @return ChatHandle[]|Collection
     */
    public function getChats(): Collection
    {
        return $this->chats;
    }

    /**
     * @deprecated
     *
     * @param ChatHandle[]|Collection $chats
     *
     * @return Customer
     */
    public function setChats(Collection $chats): Customer
    {
        $this->chats = $chats;

        return $this;
    }

    /**
     * @deprecated
     */
    public function addChat(ChatHandle $chat): Customer
    {
        $this->getChats()->append($chat);

        return $this;
    }

    /**
     * @return Email[]|Collection
     */
    public function getEmails(): Collection
    {
        return $this->emails;
    }

    /**
     * @return string|null
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
     *
     * @return Customer
     */
    public function setEmails(Collection $emails): Customer
    {
        $this->emails = $emails;

        return $this;
    }

    public function addEmail(Email $email): Customer
    {
        $this->getEmails()->append($email);

        return $this;
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
     *
     * @return Customer
     */
    public function setPhones(Collection $phones): Customer
    {
        $this->phones = $phones;

        return $this;
    }

    public function addPhone(Phone $phone): Customer
    {
        $this->getPhones()->append($phone);

        return $this;
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
     *
     * @return Customer
     */
    public function setSocialProfiles(Collection $socialProfiles): Customer
    {
        $this->socialProfiles = $socialProfiles;

        return $this;
    }

    public function addSocialProfile(SocialProfile $profile): Customer
    {
        $this->getSocialProfiles()->append($profile);

        return $this;
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
     *
     * @return Customer
     */
    public function setWebsites(Collection $websites): Customer
    {
        $this->websites = $websites;

        return $this;
    }

    public function addWebsite(Website $website): Customer
    {
        $this->getWebsites()->append($website);

        return $this;
    }
}
