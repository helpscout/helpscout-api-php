<?php

class DeleteConversationTest extends TestCase {
    public function testCanDeleteConversation()
    {
        $client = $this->getTestClient('DeleteConversation-200', 'delete');
        $this->assertTrue($client->deleteConversation(1560007));
    }

    /**
     * @expectedException \HelpScout\ApiException
     */
    public function testCanFailDeleteConversationWithMessage()
    {
        $client = $this->getTestClient('DeleteConversation-404', 'delete');
        $client->deleteConversation(1560007);
    }
}

/* End of file DeleteConversationTest.php */
