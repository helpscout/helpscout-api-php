<?php
include_once 'ApiClient.php';

use HelpScout\ApiClient;

$client = ApiClient::getInstance();
$client->setKey('example-key');

// In this example, I want to start a new conversation that will get
// emailed to the customer

// 1. First need to decide who I'm sending it to.
// All I have is an email address. This may or may not be an existing customer.
// Either way, Help Scout will create the customer if the customer does not yet exist.
$customerRef = $client->getCustomerRefProxy(null, 'customer@example.com');


// 2. Decide which mailbox this conversation will be created in
$mailboxRef = $client->getMailboxProxy(2431);

// 3. Now let's start constructing the conversation
$conversation = new \HelpScout\model\Conversation();
$conversation->setSubject('Thanks for contacting us');
$conversation->setMailbox($mailboxRef);
$conversation->setCustomer($customerRef);

// 4. Let's set the conversation type to "email" (as opposed to a chat or phone call)
$conversation->setType('email');

// 5. Every conversation MUST HAVE at least one thread.
// To send an email to the customer, the thread type must be a "Message" thread
$thread = new \HelpScout\model\thread\Message();
$thread->setBody('Hey there - sorry to hear you\'ve had trouble using our product. We\'ve contacted an engineer and he will be touching base shortly');

// 6. Now, we have to say "who" created the message. Message threads can only be created by
// registered users of Help Scout. So it must be from soneone on your team.

// 6.1 You could use the person associated with the current API key:
// $userRef = $client->getUserMe()->toRef();

// 6.2 You could use a specific user
$userRef = $client->getUserRefProxy(1234);

$thread->setCreatedBy($userRef);

// Need to cc or bcc anyone?
//$thread->setCcList(array("foo@example.com", "bar@example.com"));
//$thread->setBccList(array("foobar@example.com", "barfoo@example.com"));

//7. Add the thread to the conversation
$conversation->addLineItem($thread);

// 8. Set the conversation "createdBy" (usually same person that creates the message thread)
$conversation->setCreatedBy($userRef);

$client->createConversation($conversation);

echo $conversation->getId();

// The conversation was created in Help Scout - and Help Scout will proceed to prepare
// an email, attaching your mailbox signature, etc and send it off to the customer
// as if you'd sent it via the web interface.

