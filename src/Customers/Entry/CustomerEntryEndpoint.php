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

    public function createAddress(int $customerId, Address $address): ?int
    {
        return $this->restClient->createResource(
            $address,
            sprintf(self::CUSTOMER_ADDRESS, $customerId)
        );
    }

    public function updateAddress(int $customerId, Address $address): void
    {
        $this->restClient->updateResource(
            $address, sprintf(self::CUSTOMER_ADDRESS, $customerId)
        );
    }

    public function deleteAddress(int $customerId): void
    {
        $this->restClient->deleteResource(
            sprintf(self::CUSTOMER_ADDRESS, $customerId)
        );
    }

    public function createChat(int $customerId, ChatHandle $chat): ?int
    {
        return $this->restClient->createResource(
            $chat,
            sprintf(self::CREATE_CUSTOMER_CHAT, $customerId)
        );
    }

    public function updateChat(int $customerId, ChatHandle $chat): void
    {
        $this->restClient->updateResource(
            $chat,
            sprintf(self::CUSTOMER_CHAT, $customerId, $chat->getId())
        );
    }

    public function deleteChat(int $customerId, int $chatId): void
    {
        $this->restClient->deleteResource(
            sprintf(self::CUSTOMER_CHAT, $customerId, $chatId)
        );
    }

    public function createEmail(int $customerId, Email $email): ?int
    {
        return $this->restClient->createResource(
            $email,
            sprintf(self::CREATE_CUSTOMER_EMAIL, $customerId)
        );
    }

    public function updateEmail(int $customerId, Email $email): void
    {
        $this->restClient->updateResource(
            $email,
            sprintf(self::CUSTOMER_EMAIL, $customerId, $email->getId())
        );
    }

    public function deleteEmail(int $customerId, int $emailId): void
    {
        $this->restClient->deleteResource(
            sprintf(self::CUSTOMER_EMAIL, $customerId, $emailId)
        );
    }

    public function createPhone(int $customerId, Phone $phone): ?int
    {
        return $this->restClient->createResource(
            $phone,
            sprintf(self::CREATE_CUSTOMER_PHONE, $customerId)
        );
    }

    public function updatePhone(int $customerId, Phone $phone): void
    {
        $this->restClient->updateResource(
            $phone,
            sprintf(self::CUSTOMER_PHONE, $customerId, $phone->getId())
        );
    }

    public function deletePhone(int $customerId, int $phoneId): void
    {
        $this->restClient->deleteResource(
            sprintf(self::CUSTOMER_PHONE, $customerId, $phoneId)
        );
    }

    public function createSocialProfile(int $customerId, SocialProfile $socialProfile): ?int
    {
        return $this->restClient->createResource(
            $socialProfile,
            sprintf(self::CREATE_CUSTOMER_SOCIAL, $customerId)
        );
    }

    public function updateSocialProfile(int $customerId, SocialProfile $socialProfile): void
    {
        $this->restClient->updateResource(
            $socialProfile,
            sprintf(self::CUSTOMER_SOCIAL, $customerId, $socialProfile->getId())
        );
    }

    public function deleteSocialProfile(int $customerId, int $socialProfileId): void
    {
        $this->restClient->deleteResource(
            sprintf(self::CUSTOMER_SOCIAL, $customerId, $socialProfileId)
        );
    }

    public function createWebsite(int $customerId, Website $website): ?int
    {
        return $this->restClient->createResource(
            $website,
            sprintf(self::CREATE_CUSTOMER_WEBSITE, $customerId)
        );
    }

    public function updateWebsite(int $customerId, Website $website): void
    {
        $this->restClient->updateResource(
            $website,
            sprintf(self::CUSTOMER_WEBSITE, $customerId, $website->getId())
        );
    }

    public function deleteWebsite(int $customerId, int $websiteId): void
    {
        $this->restClient->deleteResource(
            sprintf(self::CUSTOMER_WEBSITE, $customerId, $websiteId)
        );
    }
}
