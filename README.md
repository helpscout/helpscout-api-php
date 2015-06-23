Help Scout PHP Wrapper [![Build Status](https://travis-ci.org/helpscout/helpscout-api-php.svg)](https://travis-ci.org/helpscout/helpscout-api-php)
================================================================================
> PHP Wrapper for the Help Scout API and Webhooks implementation. More information on our [developer site](http://developer.helpscout.net).

Version 1.6.1 Released
---------------------
Please see the [Changelog](https://github.com/helpscout/helpscout-api-php/blob/master/CHANGELOG.md) for details.

Requirements
---------------------
* PHP 5.3.x
* curl

Example Usage: API
---------------------
```
include 'HelpScout/ApiClient.php';

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
```

Field Selectors
---------------------
Field selectors can be given as a string or an array.

When field selectors are used, a JSON object is returned with the specificed fields. If no fields are given, you will be given the proper object. For example, the following code will return a JSON object with fields for 'name' and 'email'.
```
$mailbox = ApiClient::getInstance()->getMailbox(99, array('name','email'));
```
### Returned JSON
```
{
    "name": "My Mailbox",
    "email": "help@mymailbox.com"
}
```

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

### Reports (available via Service Descriptions)

* getConversationsReport()
* getConversationsBusyTimesReport()
* getNewConversationsReport()
* getConversationsDrillDownReport()
* getConversationsDrillDownByFieldReport()
* getNewConversationsDrillDownReport()
* getDocsReport()
* getHappinessReport()
* getHappinessRatingsReport()
* getProductivityReport()
* getFirstResponseTimeProductivityReport()
* getRepliesSentProductivityReport()
* getResolvedProductivityReport()
* getResolutionTimeProductivityReport()
* getResponseTimeProductivityReport()
* getProductivityDrillDownReport()
* getTeamReport()
* getCustomersHelpedTeamReport()
* getTeamDrillDownReport()
* getUserReport()
* getUserConversationHistoryReport()
* getUserCustomersHelpedReport()
* getUserDrillDownReport()
* getUserRepliesReport()
* getUserResolutionsReport()
* getUserHappinessReport()
* getUserRatingsReport()

Example Usage: Reports
------------------------
```
include 'HelpScout/ApiClient.php';

use HelpScout\ApiClient;

$scout = ApiClient::getInstance();
$scout->setKey('your-api-key-here');

$report = $scout->getConversationsReport([
	'start' => '2014-01-01T00:00:00Z',
	'end' => '2014-12-31T23:59:59Z'
]);
```

Report methods are not hard coded into the `ApiClient` class, but rather they are "described" via Service Descriptions. Service Descriptions are PHP configuration arrays that declare the method name, where and how to call the API, and any parameters available and/or required. 

URL configuration parameters are sent to the `ApiClient` method via a single configuration array parameter. `$scout->getDocsReport($config)`.

A list of available reporting methods is available by calling `$scout->getServiceDescriptionMethods()`. 

Example Usage: Webhooks
------------------------
```
include 'HelpScout/Webhook.php';

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
```

Debugging
------------------------

Enable debugging by calling the `setDebug(true)` method.

The `setDebug` method accepts two parameters: The first is a `boolean` to turn debugging on or off (`true` = on, `false` = off). The second (optional) parameter is a directory in which to save a debug output file. If no directory is passed, the output will echo instead of writing to a log file.

### Example output

```
[Apr 02 20:54:28] DEBUG: request = {"id":49424262,"firstName":"John","lastName":"Doe","photoUrl":null,"photoType":null,"gender":"unknown","age":null,"organization":null,"jobTitle":null,"location":"Dallas, TX","createdAt":"2015-04-01T18:08:10Z","modifiedAt":"2015-04-02T15:09:37Z","background":null,"address":{"id":5678,"lines":["123 Main Street"],"city":"Dallas","state":"","postalCode":74206,"country":"US","createdAt":null,"modifiedAt":null},"socialProfiles":[],"emails":[],"phones":[],"chats":[],"websites":[]}; context: {"method":"PUT"}
[Apr 02 20:54:28] DEBUG: response = {"code":400,"error":"Input could not be validated","validationErrors":[{"property":"address:state","value":null,"message":"Value is required"}]}; context: {"method":"PUT"}
[Apr 02 20:54:28] ERROR: Input could not be validated; context: {"method":"PUT","code":400,"errors":[{"property":"address:state","value":null,"message":"Value is required"}]}

```
Debug lines consist of four parts: Timestamp `[Apr 02 20:54:28]`, Level `DEBUG`, Message, and Context.

The example above debugging output represents 3 lines of debug text, all occurring within the same API call, a `PUT` method to update a Customer. The first is the request JSON, the second is the response JSON, and the third is an API error and its response from the server.
