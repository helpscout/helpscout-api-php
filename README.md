# Help Scout API PHP Client

[![Build Status](https://travis-ci.org/helpscout/helpscout-api-php.svg?branch=master)](https://travis-ci.com/helpscout/helpscout-api-php)
[![Maintainability](https://api.codeclimate.com/v1/badges/73d6bfd2fddd8483f8c8/maintainability)](https://codeclimate.com/repos/5c19426f34451a02c4000cab/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/73d6bfd2fddd8483f8c8/test_coverage)](https://codeclimate.com/repos/5c19426f34451a02c4000cab/test_coverage)

This is the official Help Scout PHP client. This client contains methods for easily interacting with the [Help Scout Mailbox API](http://developer.helpscout.net/help-desk-api-v2/).

## Requirements

* PHP >= 7.1

## Table of Contents

 * [Installation](#installation)
 * [Usage](#usage)
   * [Customers](#customers)
   * [Mailboxes](#mailboxes)
   * [Conversations](#conversations)
    * [Threads](#threads)
     * [Attachments](#attachments)
   * [Tags](#tags)
   * [Users](#users)
   * [Reports](#reports)
   * [Webhooks](#webhooks)
   * [Workflows](#workflows)
 * [Error Handling](#error-handling)
   * [Validation](#validation)
 * [Pagination](#pagination)
 * [Testing](#testing)

## Installation

The recommended way to install the client is by using [Composer](https://getcomposer.org/doc/00-intro.md).

```bash
composer require helpscout/api
```

## Usage

You should always use Composer's autoloader in your application to autoload classes. All examples below assume you've already included this in your code:

```php
require_once 'vendor/autoload.php';
```

### Creating the client

Use the factory to create a client. Once created, you can set the various credentials to make requests.

```php
use HelpScout\Api\ApiClientFactory;

$client = ApiClientFactory::createClient();

// Set Auth token directly if you have it
$client->setAccessToken('abc123');

// Set Client credentials if using that grant type
$client->useClientCredentials($appId, $appSecret);

// Use legacy clientId and apiKey
$client->useLegacyToken($clientId, $apiKey);

// Use a refresh token to get a new access token
$client->useRefreshToken($appId, $appSecret, $refreshToken);
```

**Note**
The `legacy_credentials` auth method is provided for developer convenience while both v1 and v2 of the API are active. When v1 of the API sunsets on June 6th, 2019, this auth scheme will no longer be active. 

You can also pass auth credentials when you create the client.

```php
// client credentials grant
$config = [
    'auth' => [
        'type' => 'client_credentials',
        'appId' => 'asdf1234',
        'appSecret' => 'fdas4321'
    ]
];
$client = ApiClientFactory::createClient($config);

// Using Legacy credentials - deprecated and will be removed on June 6, 2019
$config = [
    'auth' => [
        'type' => 'legacy_credentials',
        'clientId' => 'asdf1234',
        'apiKey' => 'fdas4321'
    ]
];
$client = ApiClientFactory::createClient($config);

// Using a refresh token
$config = [
    'auth' => [
        'type' => 'refresh_token',
        'appId' => 'asdf1234',
        'appSecret' => 'fdas4321',
        'refreshToken' => 'asdfasdf'
    ]
];
$client = ApiClientFactory::createClient($config);
```

**Note**

All credential types will trigger a pre-flight request to get an access token (HTTP 'POST' request). To avoid this, set the access token on the client before making a request using the `setAccessToken` method on the client.
```php
$client = ApiClientFactory::createClient();
$client->setAccessToken('asdfasdf');
```
The access token will always be used if available, regardless of whether you have other credentials set or not.

### Refreshing Expired Tokens

While making API calls, if your token comes back expired you can refresh the token by:

```
$client->getAuthenticator()->fetchAccessAndRefreshToken();
```

To persist the updated token you can use the authenticator that is returned:

```
$client->getAuthenticator()->fetchAccessAndRefreshToken()->getTokens(); // array
```

### Authorization Code Flow

Because the [authorization code](https://developer.helpscout.com/mailbox-api/overview/authentication/#authorization-code-flow) is only good for a single use, you'll need to exchange the code for and access token and refresh token prior to making additional api calls.  You'll also need to persist the tokens for reuse later.

```php
$client = ApiClientFactory::createClient();
$client = $client->swapAuthorizationCodeForReusableTokens(
    $appId,
    $appSecret,
    $authorizationCode
);

$credentials = $client->getAuthenticator()->getTokens();

echo $credentials['access_token'].PHP_EOL;
echo $credentials['refresh_token'].PHP_EOL;
echo $credentials['expires_in'].PHP_EOL;
```

In addition to providing the access/refresh tokens this will set the current auth to use those tokens, so you can freely make subsequent requests without reinitializing the client.

```
// uses the one-time authorization code for auth
$client = $client->swapAuthorizationCodeForReusableTokens(
    $appId,
    $appSecret,
    $authorizationCode
);

// uses access/refresh tokens for auth
$client->users()->list();
```

### Customers

Get a customer.  Whenever getting a customer, all it's entities (email addresses, phone numbers, social profiles, etc.) come preloaded in the same request.

```php
$customer = $client->customers()->get($customerId);
```

Get customers.

```php
$customers = $client->customers()->list();
```

Get customers with a filter.

As described in the API docs the [customer list can be filtered](http://developer.helpscout.net/help-desk-api-v2/customers/list) by a variety of fields. The `CustomerFields` class
provides a simple interface to set filter values. For example:

```php
use HelpScout\Api\Customers\CustomerFilters;

$filter = (new CustomerFilters())
    ->withFirstName('Tom')
    ->withLastName('Graham');

$customers = $client->customers()->list($filter);
```

Create a customer.

```php
use HelpScout\Api\Customers\Customer;

$customer = new Customer();
$customer->setFirstName('Bob');
// ...

$client->customers()->create($customer);
```

Update a customer.

```php
// ...
$customer->setFirstName('Bob');

$client->customers()->update($customer);
```

#### Address

Create a customer address.

```php
use HelpScout\Api\Customers\Entry\Address;

$address = new Address();
$address->setCity('Boston');
// ...

$client->customerEntry()->createAddress($customerId, $address);
```

Update a customer address.

```php
// ...
$address->setCity('Boston');

$client->customerEntry()->updateAddress($customerId, $address);
```

Delete a customer address.

```php
$client->customerEntry()->deleteAddress($customerId);
```

#### Chat

Create a customer chat.

```php
use HelpScout\Api\Customers\Entry\Chat;

$chat = new Chat();
$chat->setValue('Hi, can you help me?');
$chat->setType('facebook');
// ...

$client->customerEntry()->createChat($customerId, $chat);
```

Update a customer chat.

```php
// ...
$chat->setType('facebook');

$client->customerEntry()->updateChat($customerId, $chat);
```

Delete a customer chat.

```php
$client->customerEntry()->deleteChat($customerId, $chatId);
```

#### Email

Create a customer email.

```php
use HelpScout\Api\Customers\Entry\Email;

$email = new Email();
$email->setValue('lucy@helpscout.com');
$email->setType('work');
// ...

$client->customerEntry()->createEmail($customerId, $email);
```

Update a customer email.

```php
// ...
$email->setType('home');

$client->customerEntry()->updateEmail($customerId, $email);
```

Delete a customer email.

```php
$client->customerEntry()->deleteEmail($customerId, $emailId);
```

#### Phone number

Create a customer phone.

```php
use HelpScout\Api\Customers\Entry\Phone;

$phone = new Phone();
$phone->setValue('123456789');
$phone->setType('work');
// ...

$client->customerEntry()->createPhone($customerId, $phone);
```

Update a customer phone.

```php
// ...
$phone->setType('home');

$client->customerEntry()->updatePhone($customerId, $phone);
```

Delete a customer phone.

```php
$client->customerEntry()->deletePhone($customerId, $phoneId);
```

#### Social profile

Create a customer social profile.

```php
use HelpScout\Api\Customers\Entry\SocialProfile;

$socialProfile = new SocialProfile();
$socialProfile->setValue('helpscout');
$socialProfile->setType('twitter');
// ...

$client->customerEntry()->createSocialProfile($customerId, $socialProfile);
```

Update a customer social profile.

```php
// ...
$socialProfile->setType('facebook');

$client->customerEntry()->updateSocialProfile($customerId, $socialProfile);
```

Delete a customer social profile.

```php
$client->customerEntry()->deleteSocialProfile($customerId, $socialProfileId);
```

#### Website

Create a customer website.

```php
use HelpScout\Api\Customers\Entry\Website;

$website = new Website();
$website->setValue('https://www.helpscout.com');
// ...

$client->customerEntry()->createWebsite($customerId, $website);
```

Update a customer website.

```php
// ...
$website->setValue('https://www.helpscout.net');

$client->customerEntry()->updateWebsite($customerId, $website);
```

Delete a customer website.

```php
$client->customerEntry()->deleteWebsite($customerId, $websiteId);
```

### Mailboxes

Get a mailbox.

```php
$mailbox = $client->mailboxes()->get($mailboxId);
```

Get a mailbox with pre-loaded sub-entities.

A mailbox entity has two related sub-entities:

* Fields
* Folders

Each of these sub-entities can be pre-loaded when fetching a mailbox to remove the need for multiple method calls. The `MailboxRequest` class is used
to describe which sub-entities should be pre-loaded. For example:

```php
use HelpScout\Api\Mailboxes\MailboxRequest;

$request = (new MailboxRequest)
    ->withFields()
    ->withFolders();

$mailbox = $client->mailboxes()->get($mailboxId, $request);

$fields = $mailbox->getFields();
$folders = $mailbox->getFolders();
```

Get mailboxes.

```php
$mailboxes = $client->mailboxes()->list();
```

Get mailboxes with pre-loaded sub-entities.

```php
use HelpScout\Api\Mailboxes\MailboxRequest;

$request = (new MailboxRequest)
    ->withFields()
    ->withFolders();

$mailboxes = $client->mailboxes()->list($request);
```

### Conversations

Get a conversation.

```php
$conversation = $client->conversations()->get($conversationId);
```

You can easily eager load additional information/relationships for a conversation.  For example:

```php
use HelpScout\Api\Conversations\ConversationRequest;

$request = (new ConversationRequest)
    ->withMailbox()
    ->withPrimaryCustomer()
    ->withCreatedByCustomer()
    ->withCreatedByUser()
    ->withClosedBy()
    ->withThreads()
    ->withAssignee();

$conversation = $client->conversations()->get($conversationId, $request);

$mailbox = $conversation->getMailbox();
$primaryCustomer = $conversation->getPrimaryCustomer();
```

Get conversations.

```php
$conversations = $client->conversations()->list();
```

Get conversations with pre-loaded sub-entities.

```php
use HelpScout\Api\Conversations\ConversationRequest;

$request = (new ConversationRequest)
    ->withMailbox()
    ->withPrimaryCustomer()
    ->withCreatedByCustomer()
    ->withCreatedByUser()
    ->withClosedBy()
    ->withThreads()
    ->withAssignee();

$conversations = $client->conversations()->list(null, $request);
```
Get filtered conversations

```php
use HelpScout\Api\Conversations\ConversationFilters;

$filters = (new ConversationFilters())
    ->withMailbox(1)
    ->withFolder(13)
    ->withStatus('all')
    ->withTag('testing')
    ->withAssignedTo(1771)
    ->withModifiedSince(new DateTime('2017-05-06T09:04:23+05:00'))
    ->withNumber(42)
    ->withSortField('createdAt')
    ->withSortOrder('asc')
    ->withQuery('query')
    ->withCustomFieldById(123, 'blue');

$conversations = $client->conversations()->list($filters);

```

You can even combine the filters with the pre-loaded sub-entities in one request

```php
use HelpScout\Api\Conversations\ConversationRequest;
use HelpScout\Api\Conversations\ConversationFilters;

$request = (new ConversationRequest)
    ->withMailbox()
    ->withThreads();
    
$filters = (new ConversationFilters())
    ->withMailbox(1)
    ->withFolder(13)
    ->withCustomFieldById(123, 'blue');
    
$conversations = $client->conversations()->list($filters, $request);
```

Update the custom fields on a conversation:

```php
$customField = new CustomField();
$customField->setId(10524);
$customField->setValue(new DateTime('today'));
$client->conversations()->updateCustomFields($conversationId, [$customField]);
```

Delete a conversation:

```php
$client->conversations()->delete($conversationId);
```

Update an existing conversation:

```php
$client->conversations()->move($conversationId, 18);
$client->conversations()->updateSubject($conversationId, 'Need more help please');
$client->conversations()->updateCustomer($conversationId, 6854);
$client->conversations()->publishDraft($conversationId);
$client->conversations()->updateStatus($conversationId, 'closed');
$client->conversations()->assign($conversationId, 127);
$client->conversations()->unassign($conversationId);
```

#### Threads

##### Chat Threads

Create new Chat threads for a conversation.

```php
use HelpScout\Api\Customers\Customer;
use HelpScout\Api\Conversations\Threads\ChatThread;

$thread = new ChatThread();
$customer = new Customer();
$customer->setId(163487350);

$thread->setCustomer($customer);
$thread->setText('Thanks for reaching out to us!');

$client->threads()->create($conversationId, $thread);
```

##### Customer Threads

Create new Customer threads for a conversation.

```php
use HelpScout\Api\Customers\Customer;
use HelpScout\Api\Conversations\Threads\CustomerThread;

$thread = new CustomerThread();
$customer = new Customer();
$customer->setId(163487350);

$thread->setCustomer($customer);
$thread->setText('Please help me figure this out');

$client->threads()->create($conversationId, $thread);
```

##### Note Threads

Create new Note threads for a conversation.

```php
use HelpScout\Api\Conversations\Threads\NoteThread;

$thread->setText('We are still looking into this');

$client->threads()->create($conversationId, $thread);
```

##### Phone Threads

Create new Phone threads for a conversation.

```php
use HelpScout\Api\Customers\Customer;
use HelpScout\Api\Conversations\Threads\PhoneThread;

$thread = new PhoneThread();
$customer = new Customer();
$customer->setId(163487350);

$thread->setCustomer($customer);
$thread->setText('This customer called and spoke with us directly about the delay on their order');

$client->threads()->create($conversationId, $thread);
```

##### Reply Threads

Create new Reply threads for a conversation.

```php
use HelpScout\Api\Customers\Customer;
use HelpScout\Api\Conversations\Threads\ReplyThread;

$thread = new ReplyThread();
$customer = new Customer();
$customer->setId(163487350);

$thread->setCustomer($customer);
$thread->setText("Thanks, we'll be with you shortly!");

$client->threads()->create($conversationId, $thread);
```

Get threads for a conversation.

```php
$threads = $client->threads()->list($conversationId);
```

##### Attachments

Get an attachment.

```php
$attachment = $client->attachments()->get($conversationId, $attachmentId);
$attachment->getData(); // attached file's contents
```

Create an attachment:

```php
use HelpScout\Api\Conversations\Threads\Attachments\AttachmentFactory;
use HelpScout\Api\Support\Filesystem;

$attachmentFactory = new AttachmentFactory(new Filesystem());
$attachment = $attachmentFactory->make('path/to/profile.jpg);

$attachment->getMimeType(); // image/jpeg
$attachment->getFilename(); // profile.jpg
$attachment->getData(); // base64 encoded contents of the file

$client->attachments()->create($conversationId, $threadId, $attachment);
```

Delete an attachment:

```php
$client->attachments()->delete($conversationId, $attachmentId);
```

### Tags

List the tags

```php
$tags = $client->tags()->list();
```

### Users

Get a user.

```php
$user = $client->users()->get($userId);
```

Get users.

```php
$users = $client->users()->list();
```

### Reports

When running reports using the SDK, refer to the [developer docs](https://developer.helpscout.com/mailbox-api/) for the exact endpoint, parameters, and response formats. While most of the endpoints in this SDK are little more than pass-through methods to call the API, there are a few conveniences.

First, for the `start`, `end`, `previousStart`, and `previousEnd` parameters, you may pass a formatted date-time string or any object implementing the `\DateTimeInterface` as the parameter. The client will automatically convert these objects to the proper format.

For those parameters that accept multiple values (`mailboxes`, `tags`, `types,` and `folders`), you may pass an array of values and let the client convert them to the proper format. You may also pass a single value (or a comma-separated list of values) if you like.

To run the report, use the `runReport` method available on the `ApiClient` instance. Pass the name of the report class you'd like to use as the first argument and the array of report parameters as the second argument. Be sure the keys in the parameter array match the URL params specified in the docs. The client will convert the JSON response returned by the API into an array.

```php
// Example of running the Company Overall Report
// https://developer.helpscout.com/mailbox-api/endpoints/reports/company/reports-company-overall/

use HelpScout\Api\Reports\Company;

$params = [
    // Date interval fields can be passed as an object implementing the \DateTimeInterface
    // or as a string in the 'Y-m-d\Th:m:s\Z' format. All times should be in UTC.
    'start' => new \DateTime('-7 days'),
    'end' => new \DateTimeImmutable(),
    'previousStart' => '2015-01-01T00:00:00Z',
    'previousEnd' => '2015-01-31T23:59:59Z',

    // Fields accepting multiple values can be passed as an array or a comma-separated string
    'mailboxes' => [123, 321],
    'tags' => '987,789',
    'types' => ['chat', 'email'],
    'folders' => [111, 222]
];

$report = $client->runReport(Company\Overall::class, $params);
```

### Webhooks

Get a webhook.

```php
$webhook = $client->webhooks()->get($webhookId);
```

List webhooks.

```php
$webhooks = $client->webhooks()->list();
```

Create a webhook.

The default state for a newly-created webhook is `enabled`.

```php
use HelpScout\Api\Webhooks\Webhook;

$data = [
    'url' => 'http://bad-url.com',
    'events' => ['convo.assigned', 'convo.moved'],
    'secret' => 'notARealSecret'
];
$webhook = new Webhook();
$webhook->hydrate($data);
// ...

$client->webhooks()->create($webhook);
```

Update a webhook

This operation replaces the entire webhook entity, so you must provide the secret again. Once updated, the webhook will be in the `enabled` state again.
```php
$webhook->setUrl('http://bad-url.com/really_really_bad');
$webhook->setSecret('mZ9XbGHodY');
$client->webhooks()->update($webhook);
```

Delete a webhook.

```php
$client->webhooks()->delete($webhookId);
```

#### Processing an incoming webhook
You can also use the SDK to easily process an incoming webhook.  Signature validation will happen when creating the new object, so no need to check if it is valid or not. If the signatures do not match, the constructor of the `IncomingWebhook` object will throw an `InvalidSignatureException` to let you know something is wrong.

```php
// Build using a request object that satisfies the PSR-7 RequestInterface
/** @var RequestInterface $request */
$request = new Request(...);
$secret = 'superSekretKey';
$incoming = new IncomingWebhook($request, $secret);

// Or build it from globals
$incoming = IncomingWebhook::makeFromGlobals($secret);
```

Once you have the incoming webhook object, you can check the type of payload (customer, conversation, or test) as well as retrieve the data ([see example](https://github.com/helpscout/helpscout-api-php/blob/master/examples/incoming_webhook.php)). If a customer or conversation, you can retrieve the model associated. Otherwise, you can get the payload as either an associative array or standard class object.

### Workflows

Fetch a paginated list of all workflows.
```php
$workflows = $client->workflows()->list();
```

Run a manual workflow on a list of conversations.
```php
$convos = [
    123,
    321
];
$client->workflows()->runWorkflow($id, $convos);
```

Change a workflow status to either "active" or "inactive"
```php
$client->workflows()->updateStatus($id, 'active');
```

## Error handling

Any exception thrown by the client directly will implement `HelpScout\Api\Exception` and HTTP errors will result in `Http\Client\Exception\RequestException` being thrown.

### Validation

You'll encounter a `ValidationErrorException` if there are any validation errors with the request you submitted to the API.  Here's a quick example on how to use that exception:

```php
try {
    // do something
} catch (\HelpScout\Api\Exception\ValidationErrorException $e) {
    $error = $e->getError();

    var_dump(
        // A reference id for that request.  Including this anytime you contact Help Scout support will enable
        // us to help you much more quickly
        $error->getLogRef(),

        // Details about the invalid fields in the request
        $error->getErrors()
    );
    exit;
}
```


## Pagination

When fetching a collection of entities the client will return an instance of `HelpScout\Api\Entity\Collection`. If the end point supports pagination then it will return an instance of `HelpScout\Api\Entity\PagedCollection`.

```php
/** @var PagedCollection $users */
$users = $client->users()->list();

// Iterate over the first page of results
foreach ($users as $user) {
    echo $users->getFirstName();
}

// The current page number
$users->getPageNumber();

// The total number of pages
$users->getTotalPageCount();

// Load the next page
$nextUsers = $users->getNextPage();

// Load the previous page
$previousUsers = $users->getPreviousPage();

// Load the first page
$firstUsers = $users->getFirstPage();

// Load the last page
$lastUsers = $users->getLastPage();

// Load a specific page
$otherUsers = $users->getPage(12);

// Paged results are accessible as normal arrays, so you can simply iterate over them
foreach ($otherUsers as $user) {
    echo $user->getFirstName();
}
```

## Testing

The SDK comes with a handy `mock` method on the `ApiClient` class. To use this, pass in the name of the endpoint you want to mock. You'll get a `\Mockery\MockInterface` object back. Once you set the mock, any subsequent calls to that endpoint will return the mocked object.

```php
// From within the tests/ApiClientTest.php file...
public function testMockReturnsProperMock()
{
    $client = ApiClientFactory::createClient();
    $mockedWorkflows = $client->mock('workflows');

    $this->assertInstanceOf(WorkflowsEndpoint::class, $mockedWorkflows);
    $this->assertInstanceOf(MockInterface::class, $mockedWorkflows);

    $this->assertSame(
        $mockedWorkflows,
        $client->workflows()
    );
}
```

Once you've mocked an endpoint, you may want to clear it later on. To do this, you can use the `clearMock($endpoint)` method on the `ApiClient`.
