<?php

use HelpScout\ApiClient;

try {
    $client = ApiClient::getInstance();
    $client->setKey('example-key');

    $customer = new \HelpScout\model\Customer();
    $customer->setFirstName("John");
    $customer->setLastName("Appleseed");
    $customer->setOrganization("Acme, Inc");
    $customer->setJobTitle("CEO and Co-Founder");
    $customer->setLocation("San Francisco, CA");
    $customer->setBackground("I've worked with John before and he's really great.");

    // Address
    // ~~~~~
    $address = new \HelpScout\model\customer\Address();
    $address->setLines(array("500 Main St", "Suite 23"));
    $address->setCity("San Francisco");
    $address->setState("CA");
    $address->setPostalCode("94103");
    $address->setCountry("US");
    $customer->setAddress($address);

    // Phones
    // ~~~~~
    $phoneWork = new \HelpScout\model\customer\PhoneEntry();
    $phoneWork->setValue("800-555-1212");
    $phoneWork->setLocation("work");

    $phoneHome = new \HelpScout\model\customer\PhoneEntry();
    $phoneHome->setValue("800-123-1234");
    $phoneHome->setLocation("home");

    $customer->setPhones(array($phoneWork, $phoneHome));

    // Emails: at least one email is required
    $emailHome = new \HelpScout\model\customer\EmailEntry();
    $emailHome->setValue("john@example.com");
    $emailHome->setLocation("home");

    $emailWork = new \HelpScout\model\customer\EmailEntry();
    $emailWork->setValue("appleseed@example.com");
    $emailWork->setLocation("work");

    $customer->setEmails(array($emailWork, $emailHome));

    // Social Profiles
    $facebook = new \HelpScout\model\customer\SocialProfileEntry();
    $facebook->setValue("https://facebook.com/john.appleseed");
    $facebook->setType("facebook");

    $twitter = new \HelpScout\model\customer\SocialProfileEntry();
    $twitter->setValue("https://twitter.com/johnappleseed");
    $twitter->setType("twitter");

    $customer->setSocialProfiles(array($facebook, $twitter));

    // Chats
    // ~~~~~
    $chatAim = new \HelpScout\model\customer\ChatEntry();
    $chatAim->setValue("jappleseed");
    $chatAim->setType("aim");

    $chatGTalk = new \HelpScout\model\customer\ChatEntry();
    $chatGTalk->setValue("appleseed@gmail.com");
    $chatGTalk->setType("gtalk");

    $customer->setChats(array($chatAim, $chatGTalk));

    $website1 = new \HelpScout\model\customer\WebsiteEntry();
    $website1->setValue("http://www.johnappleseed.com");

    $website2 = new \HelpScout\model\customer\WebsiteEntry();
    $website2->setValue("http://www.appleseed.com");

    $customer->setWebsites(array($website1, $website2));

    $client->createCustomer($customer);
} catch (HelpScout\ApiException $e) {
    echo $e->getMessage();
    print_r($e->getErrors());
}