#### 1.8.1 (June 13, 2016)
* Fix issue with setting the value of a dropdown custom field
* Fix bug in the `getCustomer` method for Webhook
* Add `status` to `SearchConversation` model

#### 1.8.0 (February 19, 2016)
* Added support for Custom Fields returned within a Mailbox details
* Added support for Custom Field Responses returned with a Conversation

#### 1.7.0 (February 2, 2016)
* Deprecated "Team" reports. With the arrival of "Teams" with the new Plus Plan, the previous "Team" report has been renamed "Company" report
* Added new reports methods: `getCompanyReport`, `getCustomersHelpedCompanyReport`, `getCompanyDrillDownReport`
* Aliased old Team reports: `getTeamReport` -> `getCompanyReport`, `getCustomersHelpedTeamReport` -> `getCustomersHelpedCompanyReport`, `getTeamDrillDownReport` -> `getCompanyDrillDownReport`

#### 1.6.5 (January 27, 2016)
* Fixed a bug that threw an error when trying to delete a conversation after fetching it.

#### 1.6.4 (November 24, 2015)
* Add `unassign` method to a conversation allowing anyone to be assigned
* Updated the README and the `examples/CreateCustomer.php` with examples of catching a `HelpScout\ApiException` with errors.

#### 1.6.3 (June 29, 2015)
* Added an "expect" default header set to nothing. On some API calls, a dual HTTP status response was being returned. A fix was found [here](http://the-stickman.com/web-development/php-and-curl-disabling-100-continue-header/comment-page-1). By overriding the "expect" header, the server responds correctly.
This addresses the issue pointed out in [#22](https://github.com/helpscout/helpscout-api-php/issues/22)

#### 1.6.2 (June 23, 2015)
* Added support for the new Reports endpoints via Service Descriptions.
* Added ability to add new endpoints via Service Descriptions. SD allow for an endpoint to be declared and configured via a PHP array configuration. This prevents cluttering of too many class methods.

#### 1.5.2 (May 22, 2015)
* Fixed a bug with `ApiClient::createAttachment()` that was doing a `json_decode` on an already decoded response. Also updated reference to the response hash to be retrieved via an array interface instead of the previous object method.
* Fixed a bug that caused an error when a response didn't have a location header to create a response ID from. If no location header is present, a null value is returned.
This addresses the issue pointed out in [#21](https://github.com/helpscout/helpscout-api-php/issues/21)

#### 1.5.1 (April 17, 2015)
* Fixed bug in the `conversationSearch` method that routed to the incorrect URI, using "customers" instead of "conversations".

#### 1.5.0 (April 7, 2015)
* Added new cURL abstraction wrapper using [shuber/curl](https://github.com/hamstar/curl)
* Added dependency injector to allow mocking and testing
* Added tests and fixture data
* Fixed issue where `modifiedAt` was renamed to `userModifiedAt` in the API but not the client
* Fixed issue to rename type of `unassigned` to `open`. [PR #18](https://github.com/helpscout/helpscout-api-php/pull/18)

#### 1.4.0 (April 2, 2015)
* Fixed issue that didn't allow "tag" as a valid search parameter - [#13](https://github.com/helpscout/helpscout-api-php/issues/13)
* Rewrote API error messaging to return more descriptive messages from the server
* Added more robust debugging capabilities - see [README](https://github.com/helpscout/helpscout-api-php/#debugging)

#### 1.3.11 (December 3, 2014)
* Fixed issue that would prevent from json from being constructed properly when adding an attachment to an existing thread
* Adding support for missing autoReply and reload params in createConversation. Thanks [@bradt](https://github.com/bradt)
* Clean up phpdocs

#### 1.3.10 (October 9, 2014)
* Updated header code logic to look for both "HTTP_" and normal header variations.

#### 1.3.9 (May 28, 2014)
* Exposed "openedAt" attribute on Message thread object (which indicates when a customer viewed the message)

#### 1.3.8 (April 25, 2014)
* Added support for updating the body text of a thread

#### 1.3.7 (April 18, 2014)
* Default Conversation object to active status, 'email' type
* Fixed phpdoc on getUserMe method

#### 1.3.6 (April 17, 2014)

* Default threads to their internal types to eliminate the user from having to do so.
* Default threads to active status

#### 1.3.5 (April 17, 2014)

* Bug fix to address issue in Webhook.php whereby both internal and external signatures evaluate to false, thus returning an invalid "true" allowing the request to proceed.

#### 1.3.4 (March 4, 2014)

* Added support for conversation and customer search endpoints. See [developer docs](http://developer.helpscout.net/) for more information.

#### 1.3.3 (February 4, 2014)

* Added function to get the User associated with the API key used to make the request.

#### 1.3.2 (October 21, 2013)

* Added endpoint to delete a note.

#### 1.3.1 (August 27, 2013)

* Added support for the `getThreadSource` endpoint (to retrieve original email source). See [developer docs](http://developer.helpscout.net/conversations/thread/source/) for more information.
* Consistent usage of tabs for indentation (jeffbyrnes)
* Clean up documentation, add DocBlocks for every method of `ApiClient` (jeffbyrnes)
* Clarify `model\Mailbox::toRef()` DocBlock (jeffbyrnes)

#### 1.3.0 (August 23, 2013)

* Use correct value for all instances of `CURLOPT_SSL_VERIFYHOST` (thanks jeffbyrnes)
* Improved attribute handling in model constructors
* Updated to use `require_once` instead of `require` (where applicable)

#### 1.2.9 (August 22, 2013)

* Fixed use of invalid cURL option
* Update dynamic-custom-app.php example to prevent unauthorized requests from having data sent
* Added composer support

#### 1.2.8 (May 14, 2013)

* Added support for workflows (get a list of workflows or run a manual workflow). See [developer docs](http://developer.helpscout.net/workflows/list/) for more information.

#### 1.2.7 (April 4, 2013)

* Updated examples.
* When creating a conversation or thread, the createdAt property is correctly set now.
* Fixed a typo that was causing 'closedBy' to not be set properly on a conversation.

#### 1.2.6 (March 28, 2013)

* Fixed an issue where a conversation created without a customer id was causing an exception.

#### 1.2.5 (March 1, 2013)

* Updated with support for new line item properties (actionType and actionSourceId). See [developer docs](http://developer.helpscout.net/) for more information.

#### 1.2.4 (January 21, 2013)

* Conversations and threads can now be marked as 'imported' at creation time.

#### 1.2.3 (December 7, 2012)

* Added 'phone' conversation and thread type.

#### 1.2.2 (November 5, 2012)

* Added a method to retrieve a list of customers for a mailbox.

#### 1.2.1 (October 31, 2012)

* Added examples for write endpoints (create/update conversation, create/update customer).

#### 1.2.0 (October 25, 2012)

* Conversation write endpoints added. Conversations can now be created, updated, and deleted. Threads can be created, and attachments can be created and associated with a thread.
* Customer write endpoints added. Customers can now be created and updated.
* Customers can now be searched for by name and/or email.

#### 1.1.0 (October 16, 2012)

* Conversation now has a type property that specifies if the type of conversation is an 'email' or 'chat'.
* UserRef and CustomerRef have been removed and replaced by a PersonRef. The PersonRef class has a 'type' property that specifies if the person is a 'user' or a 'customer'.
* A new Chat thread type was added.
