<?php

use HelpScout\ApiClient;

$client = ApiClient::getInstance();
$client->setKey('example-key');

$customer = $client->getCustomer(1);

$customer->setFirstName("John");
$customer->setLastName("Appleseed");

// Update emails. This example can be used for other customer
// entries as well (social profiles, websites, chats, phones)
// ~~~~~
foreach ($customer->getEmails() as $email) {
	if ($email->getValue() === 'john@example.com') {
		// Update an email
		$email->setValue("test@example.com");
	} else if ($email->getValue === 'appleseed@example.com') {
		// Delete an email be prefixing the id with a minus sign
		$email->setId(-1);
	}
}

// Add a new email
$newEmail = new \HelpScout\model\customer\EmailEntry();
$newEmail->setValue("test2@example.com");
$newEmail->setLocation("home");

$emails = $customer->getEmails();
$emails[] = $newEmail;
$customer->setEmails($emails);

// Update address
// ~~~~~
$customer->getAddress()->setLines("100 Maple St", "Suite 22");
$customer->getAddress()->setCity("Los Angeles");
$customer->getAddress()->setState("CA");
$customer->getAddress()->setPostalCode("90210");

$client->updateCustomer($customer);
