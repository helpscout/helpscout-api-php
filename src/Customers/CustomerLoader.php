<?php

declare(strict_types=1);

namespace HelpScout\Api\Customers;

use HelpScout\Api\Customers\Entry\Address;
use HelpScout\Api\Customers\Entry\ChatHandle;
use HelpScout\Api\Customers\Entry\Email;
use HelpScout\Api\Customers\Entry\Phone;
use HelpScout\Api\Customers\Entry\SocialProfile;
use HelpScout\Api\Customers\Entry\Website;
use HelpScout\Api\Entity\LinkedEntityLoader;

/**
 * This loader is responsible for making additional http requests to fetch additional
 * entities without any additional steps from the user of the SDK.  Since all Customer
 * entities are loaded eagerly, this is officially deprecated.
 *
 * @deprecated
 */
class CustomerLoader extends LinkedEntityLoader
{
    public function load()
    {
        /** @var Customer $customer */
        $customer = $this->getEntity();

        if ($this->shouldLoadResource(CustomerLinks::ADDRESS)) {
            $address = $this->loadResource(Address::class, CustomerLinks::ADDRESS);
            $customer->setAddress($address);
        }

        if ($this->shouldLoadResource(CustomerLinks::CHATS)) {
            $chats = $this->loadResources(ChatHandle::class, CustomerLinks::CHATS);
            $customer->setChatHandles($chats);
        }

        if ($this->shouldLoadResource(CustomerLinks::EMAILS)) {
            $emails = $this->loadResources(Email::class, CustomerLinks::EMAILS);
            $customer->setEmails($emails);
        }

        if ($this->shouldLoadResource(CustomerLinks::PHONES)) {
            $phones = $this->loadResources(Phone::class, CustomerLinks::PHONES);
            $customer->setPhones($phones);
        }

        if ($this->shouldLoadResource(CustomerLinks::SOCIAL_PROFILES)) {
            $profiles = $this->loadResources(SocialProfile::class, CustomerLinks::SOCIAL_PROFILES);
            $customer->setSocialProfiles($profiles);
        }

        if ($this->shouldLoadResource(CustomerLinks::WEBSITES)) {
            $websites = $this->loadResources(Website::class, CustomerLinks::WEBSITES);
            $customer->setWebsites($websites);
        }
    }
}
