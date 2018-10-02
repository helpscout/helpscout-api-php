<?php

class CreateBeaconChatTest extends TestCase {
    public function testCanCreateBeaconChatConversation()
    {
        $client = $this->getTestClient('BeaconChatConversation', 'get');
        $conversation = $client->getConversation(1);

        $this->assertInstanceOf(\HelpScout\model\Conversation::class, $conversation);
    }
}

/* End of file CreateBeaconChatTest.php */
