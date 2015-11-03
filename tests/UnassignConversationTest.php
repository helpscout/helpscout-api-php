<?php

use HelpScout\model\Conversation;
use HelpScout\model\ref\MailboxRef;
use HelpScout\model\ref\PersonRef;

class UnassignConversationTest extends TestCase
{
    public function testNoOwnerSet()
    {
        $conversation = $this->getConversation();

        $this->assertFalse(array_key_exists('owner', $conversation->getObjectVars()));
    }

    public function testUnassign()
    {
        $conversation = $this->getConversation();
        $conversation->unassign();

        $this->assertTrue(array_key_exists('owner', $conversation->getObjectVars()));
        $this->assertNull($conversation->getObjectVars()['owner']);
    }

    private function getConversation()
    {
        $conversation = new Conversation;
        $conversation->setMailbox(new MailboxRef);
        $conversation->setCreatedBy(new PersonRef);

        return $conversation;
    }
}