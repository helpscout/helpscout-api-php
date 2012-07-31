helpscout-api-php
=================
PHP Wrapper for the Help Scout API.

Requirements
---------------------
* PHP 5.3.x
* curl

Example Usage
---------------------
<pre><code>
include 'HelpScout/ApiClient.php';

use HelpScout\ApiClient;

ApiClient::getInstance()->setKey('your-api-key-here');

$mailboxes = ApiClient::getInstance()->getMailboxes();
if ($mailboxes) {
    // do something
}

$mailbox = ApiCllient::getInstance()->getMailbox(99);
if ($mailbox) {
    $mailboxName = $mailbox->getName();
    $folders = $mailbox->getFolders();
    // do something
}

$conversation = ApiClient::getInstance()->getConversation(999);
if ($conversation) {
    // do something
    $threads = $conversation->getThreads();
    foreach($threads as $thread) {
        if ($thread instanceof \HelpScout\model\thread\LineItem) {
          // do something else
          continue;
        }
        if ($thread instanceof \HelpScout\model\thread\ConversationThread) {
          // do something again
        }
    }
}
</code></pre>