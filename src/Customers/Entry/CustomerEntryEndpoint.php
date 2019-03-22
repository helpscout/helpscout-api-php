<?php

declare(strict_types=1);

namespace HelpScout\Api\Customers\Entry;

use HelpScout\Api\Endpoint;

class CustomerEntryEndpoint extends Endpoint
{
    public const CUSTOMER_ADDRESS = '/v2/customers/%d/address';
    public const CREATE_CUSTOMER_CHAT = '/v2/customers/%d/chats';
    public const CUSTOMER_CHAT = '/v2/customers/%d/chats/%d';
    public const CREATE_CUSTOMER_EMAIL = '/v2/customers/%d/emails';
    public const CUSTOMER_EMAIL = '/v2/customers/%d/emails/%d';
    public const CREATE_CUSTOMER_PHONE = '/v2/customers/%d/phones';
    public const CUSTOMER_PHONE = '/v2/customers/%d/phones/%d';
    public const CREATE_CUSTOMER_SOCIAL = '/v2/customers/%d/social-profiles';
    public const CUSTOMER_SOCIAL = '/v2/customers/%d/social-profiles/%d';
    public const CREATE_CUSTOMER_WEBSITE = '/v2/customers/%d/websites';
    public const CUSTOMER_WEBSITE = '/v2/customers/%d/websites/%d';

    /**
     * @param int     $customerId
     * @param Address $address
     *
     * @return int|null
     */
    public function createAddress(int $customerId, Address $address): ?int
    {
        return $this->restClient->createResource(
            $address,
            sprintf(self::CUSTOMER_ADDRESS, $customerId)
        );
    }

    /**
     * @param int     $customerId
     * @param Address $address
     */
    public function updateAddress(int $customerId, Address $address): void
    {
        $this->restClient->updateResource(
            $address, sprintf(self::CUSTOMER_ADDRESS, $customerId)
        );
    }

    /**
     * @param int $customerId
     */
    public function deleteAddress(int $customerId): void
    {
        $this->restClient->deleteResource(
            sprintf(self::CUSTOMER_ADDRESS, $customerId)
        );
    }

    /**
     * @param int        $customerId
     * @param ChatHandle $chat
     *
     * @return int|null
     */
    public function createChat(int $customerId, ChatHandle $chat): ?int
    {
        return $this->restClient->createResource(
            $chat,
            sprintf(self::CREATE_CUSTOMER_CHAT, $customerId)
        );
    }

    /**
     * @param int        $customerId
     * @param ChatHandle $chat
     */
    public function updateChat(int $customerId, ChatHandle $chat): void
    {
        $this->restClient->updateResource(
            $chat,
            sprintf(self::CUSTOMER_CHAT, $customerId, $chat->getId())
        );
    }

    /**
     * @param int $customerId
     * @param int $chatId
     */
    public function deleteChat(int $customerId, int $chatId): void
    {
        $this->restClient->deleteResource(
            sprintf(self::CUSTOMER_CHAT, $customerId, $chatId)
        );
    }

    /**
     * @param int   $customerId
     * @param Email $email
     *
     * @return int|null
     */
    public function createEmail(int $customerId, Email $email): ?int
    {
        return $this->restClient->createResource(
            $email,
            sprintf(self::CREATE_CUSTOMER_EMAIL, $customerId)
        );
    }

    /**
     * @param int   $customerId
     * @param Email $email
     */
    public function updateEmail(int $customerId, Email $email): void
    {
        $this->restClient->updateResource(
            $email,
            sprintf(self::CUSTOMER_EMAIL, $customerId, $email->getId())
        );
    }

    /**
     * @param int $customerId
     * @param int $emailId
     */
    public function deleteEmail(int $customerId, int $emailId): void
    {
        $this->restClient->deleteResource(
            sprintf(self::CUSTOMER_EMAIL, $customerId, $emailId)
        );
    }

    /**
     * @param int   $customerId
     * @param Phone $phone
     *
     * @return int|null
     */
    public function createPhone(int $customerId, Phone $phone): ?int
    {
        return $this->restClient->createResource(
            $phone,
            sprintf(self::CREATE_CUSTOMER_PHONE, $customerId)
        );
    }

    /**
     * @param int   $customerId
     * @param Phone $phone
     */
    public function updatePhone(int $customerId, Phone $phone): void
    {
        $this->restClient->updateResource(
            $phone,
            sprintf(self::CUSTOMER_PHONE, $customerId, $phone->getId())
        );
    }

    /**
     * @param int $customerId
     * @param int $phoneId
     */
    public function deletePhone(int $customerId, int $phoneId): void
    {
        $this->restClient->deleteResource(
            sprintf(self::CUSTOMER_PHONE, $customerId, $phoneId)
        );
    }

    /**
     * @param int           $customerId
     * @param SocialProfile $socialProfile
     *
     * @return int|null
     */
    public function createSocialProfile(int $customerId, SocialProfile $socialProfile): ?int
    {
        return $this->restClient->createResource(
            $socialProfile,
            sprintf(self::CREATE_CUSTOMER_SOCIAL, $customerId)
        );
    }

    /**
     * @param int           $customerId
     * @param SocialProfile $socialProfile
     */
    public function updateSocialProfile(int $customerId, SocialProfile $socialProfile): void
    {
        $this->restClient->updateResource(
            $socialProfile,
            sprintf(self::CUSTOMER_SOCIAL, $customerId, $socialProfile->getId())
        );
    }

    /**
     * @param int $customerId
     * @param int $socialProfileId
     */
    public function deleteSocialProfile(int $customerId, int $socialProfileId): void
    {
        $this->restClient->deleteResource(
            sprintf(self::CUSTOMER_SOCIAL, $customerId, $socialProfileId)
        );
    }

    /**
     * @param int     $customerId
     * @param Website $website
     *
     * @return int|null
     */
    public function createWebsite(int $customerId, Website $website): ?int
    {
        return $this->restClient->createResource(
            $website,
            sprintf(self::CREATE_CUSTOMER_WEBSITE, $customerId)
        );
    }

    /**
     * @param int     $customerId
     * @param Website $website
     */
    public function updateWebsite(int $customerId, Website $website): void
    {
        $this->restClient->updateResource(
            $website,
            sprintf(self::CUSTOMER_WEBSITE, $customerId, $website->getId())
        );
    }

    /**
     * @param int $customerId
     * @param int $websiteId
     */
    public function deleteWebsite(int $customerId, int $websiteId): void
    {
        $this->restClient->deleteResource(
            sprintf(self::CUSTOMER_WEBSITE, $customerId, $websiteId)
        );
    }
}
