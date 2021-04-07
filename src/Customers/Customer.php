<?php

declare(strict_types=1);

namespace HelpScout\Api\Customers;

use DateTime;
use HelpScout\Api\Assert\Assert;
use HelpScout\Api\Customers\Entry\Address;
use HelpScout\Api\Customers\Entry\ChatHandle;
use HelpScout\Api\Customers\Entry\Email;
use HelpScout\Api\Customers\Entry\Phone;
use HelpScout\Api\Customers\Entry\Property;
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

    /**
     * @var Property[]|Collection
     */
    private $properties;

    public function __construct()
    {
        $this->chats = new Collection();
        $this->emails = new Collection();
        $this->phones = new Collection();
        $this->socialProfiles = new Collection();
        $this->websites = new Collection();
        $this->properties = new Collection();
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

        if (isset($embedded['properties']) && is_array($embedded['properties'])) {
            $properties = $this->hydrateMany(Property::class, $embedded['properties']);

            $this->setProperties($properties);
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
            'phone' => $this->getFirstPhone(),

            'address' => $this->getAddress() !== null ? $this->getAddress()->extract() : null,

            'emails' => $this->getEmails()->extract(),
            'phones' => $this->getPhones()->extract(),
            'chats' => $this->getChatHandles()->extract(),
            'socialProfiles' => $this->getSocialProfiles()->extract(),
            'websites' => $this->getWebsites()->extract(),
        ]);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): Customer
    {
        Assert::greaterThan($id, 0);

        $this->id = $id;

        return $this;
    }

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt = null): Customer
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt = null): Customer
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): Customer
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): Customer
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): Customer
    {
        $this->gender = $gender;

        return $this;
    }

    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    public function setJobTitle(?string $jobTitle): Customer
    {
        $this->jobTitle = $jobTitle;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): Customer
    {
        $this->location = $location;

        return $this;
    }

    public function getOrganization(): ?string
    {
        return $this->organization;
    }

    public function setOrganization(?string $organization): Customer
    {
        $this->organization = $organization;

        return $this;
    }

    public function getPhotoType(): ?string
    {
        return $this->photoType;
    }

    public function setPhotoType(?string $photoType): Customer
    {
        $this->photoType = $photoType;

        return $this;
    }

    public function getPhotoUrl(): ?string
    {
        return $this->photoUrl;
    }

    public function setPhotoUrl(?string $photoUrl): Customer
    {
        $this->photoUrl = $photoUrl;

        return $this;
    }

    public function getBackground(): ?string
    {
        return $this->background;
    }

    public function setBackground(?string $background): Customer
    {
        $this->background = $background;

        return $this;
    }

    public function getAge(): ?string
    {
        return $this->age;
    }

    public function setAge(?string $age): Customer
    {
        $this->age = $age;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

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
     */
    public function setChatHandles(Collection $chats): Customer
    {
        $this->chats = $chats;

        return $this;
    }

    /**
     * @param ChatHandle|string $chat
     * @param string            $type
     */
    public function addChatHandle($chat, string $type = null): Customer
    {
        if (is_string($chat)) {
            $newChatHandle = new ChatHandle();
            $newChatHandle->hydrate([
                'value' => $chat,
                'type' => $type,
            ]);
            $chat = $newChatHandle;
        }

        $this->getChatHandles()->append($chat);

        return $this;
    }

    /**
     * @return Email[]|Collection
     */
    public function getEmails(): Collection
    {
        return $this->emails;
    }

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
    public function setEmails(Collection $emails): Customer
    {
        $this->emails = $emails;

        return $this;
    }

    /**
     * @param Email|string $email
     * @param string       $type
     */
    public function addEmail($email, string $type = null): Customer
    {
        if (is_string($email)) {
            $newEmail = new Email();
            $newEmail->hydrate([
                'value' => $email,
                'type' => $type,
            ]);
            $email = $newEmail;
        }

        $this->getEmails()->append($email);

        return $this;
    }

    public function getFirstPhone(): ?string
    {
        $phones = $this->phones->toArray();
        $phone = array_shift($phones);

        return $phone instanceof Phone
            ? $phone->getValue()
            : null;
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
    public function setPhones(Collection $phones): Customer
    {
        $this->phones = $phones;

        return $this;
    }

    /**
     * @param Phone|string $phone
     * @param string       $type
     */
    public function addPhone($phone, string $type = null): Customer
    {
        if (is_string($phone)) {
            $newPhone = new Phone();
            $newPhone->hydrate([
                'value' => $phone,
                'type' => $type,
            ]);
            $phone = $newPhone;
        }

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
     */
    public function setSocialProfiles(Collection $socialProfiles): Customer
    {
        $this->socialProfiles = $socialProfiles;

        return $this;
    }

    /**
     * @param SocialProfile|string $profile
     * @param string               $type
     */
    public function addSocialProfile($profile, string $type = null): Customer
    {
        if (is_string($profile)) {
            $newProfile = new SocialProfile();
            $newProfile->hydrate([
                'value' => $profile,
                'type' => $type,
            ]);
            $profile = $newProfile;
        }

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
     */
    public function setWebsites(Collection $websites): Customer
    {
        $this->websites = $websites;

        return $this;
    }

    /**
     * @param Website|string $website
     */
    public function addWebsite($website): Customer
    {
        if (is_string($website)) {
            $newWebsite = new Website();
            $newWebsite->hydrate([
                'value' => $website,
            ]);
            $website = $newWebsite;
        }

        $this->getWebsites()->append($website);

        return $this;
    }

    /**
     * @return Property[]|Collection
     */
    public function getProperties(): Collection
    {
        return $this->properties;
    }

    /**
     * @param Property[]|Collection $properties
     */
    public function setProperties(Collection $properties): Customer
    {
        $this->properties = $properties;

        return $this;
    }
}
