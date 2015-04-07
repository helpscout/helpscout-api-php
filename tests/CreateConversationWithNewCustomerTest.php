<?php

class CreateConversationWithNewCustomerTest extends TestCase {
    public function testCanCreateConversation()
    {
        $client = $this->getTestClient('CreateConversationWithNewCustomer-201', 'post');

        // The customer associated with the conversation
        $customerRef = $client->getCustomerRefProxy(null, 'customer@example.com');

        $conversation = new \HelpScout\model\Conversation();
        $conversation->setType('email');
        $conversation->setSubject('I need help');
        $conversation->setCustomer($customerRef);
        $conversation->setCreatedBy($customerRef);

        // The mailbox associated with the conversation
        $conversation->setMailbox($client->getMailboxProxy(2562));

        // A conversation must have at least one thread
        $thread = new \HelpScout\model\thread\Customer();
        $thread->setBody('Hello there - I need some help please.');

        // Create by: required
        $thread->setCreatedBy($customerRef);
        $conversation->addLineItem($thread);
        $client->createConversation($conversation);

        $this->assertSame('1560125', $conversation->getId(), 'Unable to parse correct conversation id');
    }

    /**
     * @expectedException \HelpScout\ApiException
     */
    public function testCanFailCreateConversationWithMessage()
    {
        $client = $this->getTestClient('CreateConversationWithNewCustomer-400', 'post');

        // The customer associated with the conversation
        $customerRef = $client->getCustomerRefProxy(null, 'customer@example.com');

        $conversation = new \HelpScout\model\Conversation();
        $conversation->setCustomer($customerRef);
        $conversation->setCreatedBy($customerRef);
        $conversation->setMailbox($client->getMailboxProxy(2562));

        // A conversation must have at least one thread
        $thread = new \HelpScout\model\thread\Customer();

        // Create by: required
        $thread->setCreatedBy($customerRef);
        $conversation->addLineItem($thread);
        $client->createConversation($conversation);
    }
}

/* End of file CreateConversationWithNewCustomerTest.php */
