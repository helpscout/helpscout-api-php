Help Scout PHP Wrapper
======================
PHP Wrapper for the Help Scout API and Webhooks implementation. More information on our developer site: [http://developer.helpscout.net](http://developer.helpscout.net).

Version 1.2.4 Released
---------------------
Please see the [Changelog](https://github.com/helpscout/helpscout-api-php/blob/master/CHANGELOG.md) for details.

Requirements
---------------------
* PHP 5.3.x
* curl

Example Usage: API
---------------------
<pre><code>include 'HelpScout/ApiClient.php';

use HelpScout\ApiClient;

$hs = ApiClient::getInstance();
$hs->setKey('your-api-key-here');

$mailboxes = $hs->getMailboxes();
if ($mailboxes) {
    // do something
}

$mailbox = $hs->getMailbox(99);
if ($mailbox) {
    $mailboxName = $mailbox->getName();
    $folders = $mailbox->getFolders();
    // do something
}

$conversation = $hs->getConversation(999);
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

// to get page 2 of a list of conversations:
$list = $hs->getConversationsForMailbox(99, array('page' => 2));

// to get all the closed conversations:
$closed = $hs->getConversationsForMailbox(99, array('page' => 1, 'status' => 'closed'));

// to get page 2 of a list of conversations, 
// while only returning the "id" and "number" attributes on a conversation:
$convos = $hs->getConversationsForMailbox(99, array('page' => 2), array('id', 'number'));

// to get page 2 conversations from a specific folder:
$convos = $hs->getConversationsForFolder(99, 22, array('page' => 2)); // where 99=MailboxId and 22=FolderId


// to create a new conversation with a note and an attachment
$at = new \HelpScout\model\Attachment();
$at->load('/path/to/some/image.jpg');

$hs->createAttachment($at);

$note = new \HelpScout\model\thread\Note();
$note->setBody('Hey this is a note');
$note->addAttachment($at);

// if you already know the ID of the Help Scout user, you can simply get a ref
$userRef = $hs->getUserRefProxy(4);

$note->setCreatedBy($userRef);

$convo = new \HelpScout\model\Conversation();
$convo->setMailbox($hs->getMailboxProxy(2431));
$convo->setCreatedBy($userRef);
$convo->setSubject('Note test');

// every conversation must be tied to a customer
$convo->setCustomer($customerRef);

$convo->addLineItem($note);

$hs->createConversation($convo);
</code></pre>

Field Selectors
---------------------
Field selectors can be given as a string or an array.

When field selectors are used, a JSON object is returned with the specificed fields. If no fields are given, you will be given the proper object. For example, the following code will return a JSON object with fields for 'name' and 'email'.
<pre><code>$mailbox = ApiClient::getInstance()->getMailbox(99, array('name','email'));</code></pre>
### Returned JSON
<pre><code>{
    "name": "My Mailbox",
    "email": "help@mymailbox.com"	
}
</code></pre>

API Client Methods
--------------------

### Mailboxes
* getMailboxes($page=1, $fields=null)
* getMailbox($mailboxId, $fields=null)

### Folders
* getFolders($mailboxId, $page=1, $fields=null)

### Conversations
* getConversationsForFolder($mailboxId, $folderId, array $params=array(), $fields=null)
* getConversationsForMailbox($mailboxId, array $params=array(), $fields=null)
* getConversationsForCustomerByMailbox($mailboxId, $customerId, array $params=array(), $fields=null)
* getConversation($conversationId, $fields=null)
* createConversation($conversation)
* createThread($conversationId, $thread)
* updateConversation($conversation)
* deleteConversation($id)

### Attachments
* getAttachmentData($attachmentId)
* createAttachment($attachment)
* deleteAttachment($id)

### Customers
* getCustomers($page=1, $fields=null)
* searchCustomers($firstName=null, $lastName=null, $email=null, $page=1, $fields=null)
* searchCustomersByEmail($email, $page=1, $fields=null)
* searchCustomersByName($firstName, $lastName, $page=1, $fields=null)
* getCustomer($customerId, $fields=null)
* createCustomer($customer)
* updateCustomer($customer)

### Users
* getUsers($page=1, $fields=null)
* getUsersForMailbox($mailboxId, $page=1, $fields=null)
* getUser($userId, $fields=null)

Example Usage: Webhooks
------------------------
<pre><code>include 'HelpScout/Webhook.php';

$webhook = new \HelpScout\Webhook('secret-key-here');
if ($webhook->isValid()) {
  $eventType = $webhook->getEventType();
  switch($eventType) {
    case 'convo.created':
        $conversation = $webhook->getConversation();
        // do something
        break;
    case 'convo.deleted':
        $obj = $webhook->getObject();
        if ($obj) {
          $convoId = $obj->id;
          // do something
        }
        break;
    case 'customer.created':
        $customer = $webhook->getCustomer();
        // do something
        break;
  } 
}
</code></pre>
