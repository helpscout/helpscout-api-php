<?php
namespace HelpScout;

require_once 'ClassLoader.php';

use HelpScout\model\Attachment;

final class ApiClient {
	const USER_AGENT = 'Help Scout API/Php Client v1';
	const API_URL    = 'https://api.helpscout.net/v1/';
	const NAMESPACE_SEPARATOR = '\\';

	private $userAgent = false;
	private $apiKey    = false;
	private $isDebug   = false;
	private $debugDir  = false;
	private $curl      = false;

    private $services = array();

    private $serviceDescriptionLocations = array(
        'reports/conversations.php',
        'reports/docs.php',
        'reports/happiness.php',
        'reports/productivity.php',
        'reports/team.php',
        'reports/user.php'
    );

	/**
	 * @var \HelpScout\ApiClient
	 */
	private static $instance = false;

	private function __construct() {
		ClassLoader::register();
		$this->curl = new \Curl();
        $this->services = $this->loadServiceDescriptions();
	}

	/**
	 * Get an instance of the ApiClient
	 *
	 * @return \HelpScout\ApiClient
	 * @static
	 */
	public static function getInstance() {
		if (self::$instance === false) {
			self::$instance = new ApiClient();
		}
		return self::$instance;
	}

	/**
	 * Get all available service descriptions and
	 * their configurations.
	 * 
	 * @return array
	 */
    public function getServiceDescriptions()
    {
        return $this->services;
    }

    /**
     * Get the public API method names as described via 
     * a service description.
     * 
     * @return array
     */
    public function getServiceDescriptionMethods()
    {
        return array_keys($this->services);
    }

	/**
	 * Set ApiClient Curl Wrapper
	 *
	 * @param  \Curl
	 * @return void
	 */
	public function setCurl(\Curl $curl) {
		$this->curl = $curl;
	}

	/**
	 * Put ApiClient in debug mode or note.
	 *
	 * If in debug mode, you can optionally supply a directory
	 * in which to write debug messages.
	 * If no directory is set, debug messages are echo'ed out.
	 *
	 * @param  boolean        $bool
	 * @param  boolean|string $dir
	 * @return void
	 */
	public function setDebug($bool, $dir=false) {
		$this->isDebug = $bool;
		if ($dir && is_dir($dir)) {
			$this->debugDir = $dir;
		}
	}

	/**
	 * Set the API Key to use with this request
	 *
	 * @param string $apiKey
	 */
	public function setKey($apiKey) {
		$this->apiKey = $apiKey;
	}

	/**
	 * Set the User Agent to use with this request
	 *
	 * @param string $userAgent
	 */
	public function setUserAgent($userAgent) {
		$userAgent = trim($userAgent);
		if (!empty($userAgent)) {
			$this->userAgent = $userAgent;
		}
	}

	/**
	 * Get the User Agent used with this request
	 *
	 * @param string
	 */
	private function getUserAgent() {
		if ($this->userAgent) {
			return $this->userAgent . '(Version: ' . phpversion() . ')';
		}
		return self::USER_AGENT . '(Version: ' . phpversion() . ')';
	}

	/**
	 * Get a list of conversation for the given folder
	 *
	 * @param  integer      $mailboxId
	 * @param  integer      $folderId
	 * @param  array        $params
	 * @param  array|string $fields
	 * @throws \HelpScout\ApiException
	 * @return \HelpScout\Collection
	 */
	public function getConversationsForFolder($mailboxId, $folderId, array $params=array(), $fields=null) {
		if (!is_numeric($mailboxId) || $mailboxId < 1) {
			throw new ApiException(sprintf('Invalid mailboxId in getConversationsForFolder method [%s]', $mailboxId));
		}
		if (!is_numeric($folderId) || $folderId < 1) {
			throw new ApiException(sprintf('Invalid folderId in getConversationsForFolder method [%s]', $folderId));
		}
		return $this->getCollection(
			sprintf('mailboxes/%d/folders/%d/conversations.json', $mailboxId, $folderId),
			$this->getConvoParams($params, $fields),
			'getConversationsForFolder',
			'\HelpScout\model\Conversation'
		);
	}

	/**
	 * Return a collection of conversations for the given mailbox
	 *
	 * @param  integer $mailboxId
	 * @param  array   $params
	 * @param  array   $fields
	 * @throws \HelpScout\ApiException
	 * @return \HelpScout\Collection
	 */
	public function getConversationsForMailbox($mailboxId, array $params=array(), $fields=null) {
		if (!is_numeric($mailboxId) || $mailboxId < 1) {
			throw new ApiException(sprintf('Invalid mailboxId in getConversationsForMailbox method [%s]', $mailboxId));
		}
		return $this->getCollection(
			sprintf('mailboxes/%d/conversations.json', $mailboxId),
			$this->getConvoParams($params, $fields),
			'getConversationsForMailbox',
			'\HelpScout\model\Conversation'
		);
	}

	/**
	 * Return a collection of conversations for the given mailbox and customer
	 *
	 * @param  integer $mailboxId
	 * @param  integer $customerId
	 * @param  array   $params
	 * @param  array   $fields
	 * @throws \HelpScout\ApiException
	 * @return \HelpScout\Collection
	 */
	public function getConversationsForCustomerByMailbox($mailboxId, $customerId, array $params=array(), $fields=null) {
		if (!is_numeric($mailboxId) || $mailboxId < 1) {
			throw new ApiException(sprintf('Invalid mailboxId in getConversationsForCustomerByMailbox method [%s]', $mailboxId));
		}
		if (!is_numeric($customerId) || $customerId < 1) {
			throw new ApiException(sprintf('Invalid customerId in getConversationsForCustomerByMailbox method [%s]', $customerId));
		}
		return $this->getCollection(
			sprintf('mailboxes/%d/customers/%d/conversations.json', $mailboxId, $customerId),
			$this->getConvoParams($params, $fields),
			'getConversationsForCustomerByMailbox',
			'\HelpScout\model\Conversation'
		);
	}

	/**
	 * @param  integer      $conversationId
	 * @param  string|array $fields
	 * @throws \HelpScout\ApiException
	 * @return \HelpScout\model\Conversation
	 */
	public function getConversation($conversationId, $fields=null) {
		if (!is_numeric($conversationId) || ($conversationId < 1)) {
			throw new ApiException(sprintf('Invalid conversationId in getConversation method [%s]', $conversationId));
		}
		return $this->getItem(
			sprintf('conversations/%d.json', $conversationId),
			$this->getParams(array('fields' => $fields)),
			'getConversation',
			'\HelpScout\model\Conversation'
		);
	}

	/**
	 * @param integer $conversationId
	 * @param integer $threadId
	 * @throws \HelpScout\ApiException
	 * @return string
	 */
	public function getThreadSource($conversationId, $threadId) {
		$json = false;
		try {
			$json = $this->getItem(
				sprintf('conversations/%d/thread-source/%d.json', $conversationId, $threadId), null, 'getThreadSource', false
			);
		} catch(ApiException $e) {
			if ($e->getCode() !== 404) {
				throw $e;
			}
		}
		$data = false;
		if ($json) {
			$data = $json->data;
			if ($data) {
				$data = base64_decode($data);
			}
		}
		return $data;
	}

	/**
	 * @param  integer $attachmentId
	 * @throws \HelpScout\ApiException
	 * @return string
	 */
	public function getAttachmentData($attachmentId) {
		if (!is_numeric($attachmentId) || $attachmentId < 1) {
			throw new ApiException(sprintf('Invalid attachmentId in getAttachmentData method [%s]', $attachmentId));
		}
		$json = $this->getItem(
			sprintf('attachments/%d/data.json', $attachmentId), null, 'getAttachmentData', false
		);
		$data = false;
		if ($json) {
			$data = $json->data;
			if ($data) {
				$data = base64_decode($data);
			}
		}
		return $data;
	}

	private function getConvoParams(array $params, $fields) {
		return $this->getParams(array_merge($params, array('fields' => $fields)), array('page','fields','status','modifiedSince','tag'));
	}

	/**
	 * Returns a Collection of all the users for the company.
	 *
	 * @param  integer      $page
	 * @param  string|array $fields
	 * @return \HelpScout\Collection
	 */
	public function getUsers($page=1, $fields=null) {
		return $this->getCollection(
			'users.json', $this->getParams(array('fields' => $fields, 'page' => $page)), 'getUsers', '\HelpScout\model\User'
		);
	}

	/**
	 * Returns a Collection of users that have access to the given mailbox.
	 *
	 * @param  integer      $mailboxId
	 * @param  integer      $page
	 * @param  string|array $fields
	 * @return \HelpScout\Collection
	 */
	public function getUsersForMailbox($mailboxId, $page=1, $fields=null) {
		return $this->getCollection(
			sprintf('mailboxes/%d/users.json', $mailboxId), $this->getParams(array('fields' => $fields, 'page' => $page)), 'getUsersForMailbox', '\HelpScout\model\User'
		);
	}

	/**
	 * @param  integer      $userId
	 * @param  string|array $fields
	 * @throws \HelpScout\ApiException
	 * @return \HelpScout\model\User
	 */
	public function getUser($userId, $fields=null) {
		if (!is_numeric($userId) || $userId < 1) {
			throw new ApiException(sprintf('Invalid userId in getUser method [%s]', $userId));
		}
		return $this->getItem(
			sprintf('users/%d.json', $userId), $this->getParams(array('fields' => $fields)), 'getUser', '\HelpScout\model\User'
		);
	}

	/**
	 * Gets the User associated with the API key used to make the request.
	 *
	 * @param null $fields
	 * @return \HelpScout\model\User
	 */
	public function getUserMe($fields=null) {
		return $this->getItem('users/me.json', $this->getParams(array('fields' => $fields)), 'getUser', '\HelpScout\model\User');
	}

	/**
	 * Returns a Collection of all the customers for the company.
	 * @param  integer      $page
	 * @param  string|array $fields
	 * @return \HelpScout\Collection
	 */
	public function getCustomers($page=1, $fields=null) {
		return $this->getCollection(
			'customers.json', $this->getParams(array('fields' => $fields, 'page' => $page)), 'getCustomers', '\HelpScout\model\Customer'
		);
	}

	/**
	 * @param  integer       $mailboxId
	 * @param  integer       $page
	 * @param  string|array  $fields
	 * @return \HelpScout\Collection
	 */
	public function getCustomersForMailbox($mailboxId, $page=1, $fields=null) {
		return $this->getCollection(
			sprintf('mailboxes/%d/customers.json', $mailboxId), $this->getParams(array('fields' => $fields, 'page' => $page)), 'getCustomersForMailbox', '\HelpScout\model\Customer'
		);
	}

	/**
	 * @param  integer      $customerId
	 * @param  string|array $fields
	 * @throws \HelpScout\ApiException
	 * @return \HelpScout\model\Customer
	 */
	public function getCustomer($customerId, $fields=null) {
		if (!is_numeric($customerId) || $customerId < 1) {
			throw new ApiException(sprintf('Invalid customerId in getCustomer method [%s]', $customerId));
		}
		return $this->getItem(
			sprintf('customers/%d.json', $customerId), $this->getParams(array('fields' => $fields)), 'getCustomer', '\HelpScout\model\Customer'
		);
	}

	/**
	 * @param  string  $email
	 * @param  integer $page
	 * @param  array   $fields
	 * @return \HelpScout\Collection
	 */
	public function searchCustomersByEmail($email, $page=1, $fields=null) {
		$params = array('fields' => $fields, 'page' => $page, 'email' => $email);
		return $this->getCollection('customers.json', $this->getParams($params), 'searchCustomers', '\HelpScout\model\Customer');
	}

	/**
	 * @param  string  $firstName
	 * @param  string  $lastName
	 * @param  integer $page
	 * @param  array   $fields
	 * @return \HelpScout\Collection
	 */
	public function searchCustomersByName($firstName, $lastName, $page=1, $fields=null) {
		$params = array('fields' => $fields, 'page' => $page, 'firstName' => $firstName, 'lastName' => $lastName);
		return $this->getCollection('customers.json', $this->getParams($params), 'searchCustomers', '\HelpScout\model\Customer');
	}

	/**
	 * @param  string  $firstName
	 * @param  string  $lastName
	 * @param  string  $email
	 * @param  integer $page
	 * @param  array   $fields
	 * @return \HelpScout\Collection
	 */
	public function searchCustomers($firstName=null, $lastName=null, $email=null, $page=1, $fields=null) {
		$params = array('fields' => $fields, 'page' => $page, 'firstName' => $firstName, 'lastName' => $lastName, 'email' => $email);
		return $this->getCollection("customers.json", $this->getParams($params), 'searchCustomers', '\HelpScout\model\Customer');
	}

	/**
	 * @param string $query
	 * @param null $sortField
	 * @param null $sortOrder
	 * @param int $page
	 * @return \HelpScout\Collection
	 */
	public function customerSearch($query='*', $sortField=null, $sortOrder=null, $page=1) {
		$params = array('query' => $query, 'sortField' => $sortField, 'sortOrder' => $sortOrder, 'page' => $page);
		return $this->getCollection("search/customers.json", $this->getParams($params), 'customerSearch', '\HelpScout\model\SearchCustomer');
	}

	/**
	 * @param string $query
	 * @param null $sortField
	 * @param null $sortOrder
	 * @param int $page
	 * @return \HelpScout\Collection
	 */
	public function conversationSearch($query='*', $sortField=null, $sortOrder=null, $page=1) {
		$params = array('query' => $query, 'sortField' => $sortField, 'sortOrder' => $sortOrder, 'page' => $page);
		return $this->getCollection("search/conversations.json", $this->getParams($params), 'conversationSearch', '\HelpScout\model\SearchConversation');
	}

	/**
	 * @param  \HelpScout\model\Conversation $conversation
	 * @param boolean $imported
	 * @param boolean $autoReply Enables auto replies to be sent when a conversation is created via the API
	 * @param boolean $reload Return the created conversation in the response
	 * @return boolean|string
	 */
	public function createConversation(model\Conversation $conversation, $imported=false, $autoReply=false, $reload=false) {
		$url = 'conversations.json';
		$params = array();
		if ($imported) {
			$params['imported'] = 'true';
		}
		if ($autoReply) {
			$params['autoReply'] = 'true';
		}
		if ($reload) {
			$params['reload'] = 'true';
		}
		if ($params) {
			$url .= '?' . http_build_query($params);
		}
		$json = $conversation->toJSON();
		list($id, ) = $this->doPost($url, $json, 201);
		$conversation->setId($id);
	}

	/**
	 * @param  integer                                    $conversationId
	 * @param  \HelpScout\model\thread\ConversationThread $thread
	 * @param  boolean                                    $imported
	 * @return void
	 */
	public function createThread($conversationId, model\thread\ConversationThread $thread, $imported=false) {
		$url = 'conversations/' . $conversationId . '.json';
		if ($imported) {
			$url = $url . '?imported=true';
		}
		list($id, ) = $this->doPost($url, $thread->toJson(), 201);
		$thread->setId($id);
	}

	/**
	 * @param $conversationId
	 * @param $threadId
	 * @param $text
	 */
	public function updateThreadText($conversationId, $threadId, $text) {
		$json = '{ "body": ' . json_encode($text) . ' }';
		$this->doPut('conversations/' . $conversationId . '/threads/' . $threadId . '.json', $json, 200);
	}

	/**
	 * @param  \HelpScout\model\Attachment $attachment
	 * @return void
	 */
	public function createAttachment(Attachment $attachment) {
		list(,$body) = $this->doPost('attachments.json', $attachment->toJson(), 201);

		if ($body) {
			$attachment->setHash($body['item']['hash']);
		}
	}

	/**
	 * @param  integer $id
	 * @return void
	 */
	public function deleteAttachment($id) {
		$this->doDelete('attachments/' . $id . '.json', 200);
	}

	/**
	 * Deletes a note thread.
	 *
	 * @param $threadId
	 */
	public function deleteNote($threadId) {
		$this->doDelete('notes/' . $threadId . '.json', 200);
	}

	/**
	 * Update a conversation
	 *
	 * @param  \HelpScout\model\Conversation $conversation The conversation payload to PUT
	 * @param  boolean                       $reload       Return the updated conversation if true
	 * @return void
	 * @link   http://developer.helpscout.net/conversations/update/
	 * @todo   Write a way to handle the return payload if reload = true
	 *         May require a method of its own that's run on the doPost, doPut, etc. methods
	 */
	public function updateConversation(model\Conversation $conversation, $reload=false) {
		$reload_param = $reload ? '?reload=true' : '';

		$this->doPut('conversations/' . $conversation->getId() . '.json', $conversation->toJSON(), 200);
	}

	/**
	 * @param  integer $id
	 * @return void
	 */
	public function deleteConversation($id) {
		return $this->doDelete('conversations/' . $id . '.json', 200);
	}

	/**
	 * @param  \HelpScout\model\Customer $customer
	 * @return void
	 */
	public function createCustomer(model\Customer $customer) {
		list($id, ) = $this->doPost('customers.json', $customer->toJSON(), 201);
		$customer->setId($id);
	}

	/**
	 * @param  \HelpScout\model\Customer $customer
	 * @return void
	 */
	public function updateCustomer(model\Customer $customer) {
		$this->doPut('customers/' . $customer->getId() . '.json', $customer->toJSON(), 200);
	}

	/**
	 * @param  integer      $mailboxId
	 * @param  integer      $page
	 * @param  string|array $fields
	 * @throws \HelpScout\ApiException
	 * @return \HelpScout\Collection
	 */
	public function getFolders($mailboxId, $page=1, $fields=null) {
		if (!is_numeric($mailboxId) || $mailboxId < 1) {
			throw new ApiException(sprintf('Invalid mailboxId in getFolders method [%s]', $mailboxId));
		}
		return $this->getCollection(
			sprintf('mailboxes/%d/folders.json', $mailboxId), $this->getParams(array('fields' => $fields, 'page' => $page)), 'getFolders', 'HelpScout\model\Folder'
		);
	}

	/**
	 * @param  integer      $mailboxId
	 * @param  string|array $fields
	 * @throws \HelpScout\ApiException
	 * @return \HelpScout\model\Mailbox
	 */
	public function getMailbox($mailboxId, $fields=null) {
		if (!is_numeric($mailboxId) || $mailboxId < 1) {
			throw new ApiException(sprintf('Invalid mailboxId in getMailbox method [%s]', $mailboxId));
		}
		return $this->getItem(
			sprintf('mailboxes/%d.json', $mailboxId), $this->getParams(array('fields' => $fields)), 'getMailbox', '\HelpScout\model\Mailbox'
		);
	}

	/**
	 * Returns a MailboxRef object initialized with the given id.
	 *
	 * @param  integer $mailboxId
	 * @return \HelpScout\model\ref\MailboxRef
	 */
	public function getMailboxProxy($mailboxId) {
		$ref = new model\ref\MailboxRef();
		$ref->setId($mailboxId);
		return $ref;
	}

	/**
	 * Returns a MailboxRef object initialized with the given id.
	 *
	 * @param  integer $userId
	 * @return \HelpScout\model\ref\UserRef
	 */
	public function getUserRefProxy($userId) {
		$ref = new model\ref\UserRef();
		$ref->setId($userId);
		return $ref;
	}

	/**
	 * Returns a CustomerRef object initialized with the given HelpScout
	 * Customer ID and email (optional)
	 *
	 * @param  integer         $customerId
	 * @param  boolean|integer $customerEmail
	 * @return \HelpScout\model\ref\CustomerRef
	 */
	public function getCustomerRefProxy($customerId, $customerEmail = false) {
		$ref = new model\ref\CustomerRef();
		$ref->setId($customerId);
		if ( $customerEmail ) $ref->setEmail($customerEmail);
		return $ref;
	}

	/**
	 * Get a list of Mailboxes for the given user
	 * @param  integer      $page
	 * @param  string|array $fields
	 * @return \HelpScout\Collection
	 */
	public function getMailboxes($page=1, $fields=null) {
		return $this->getCollection(
			'mailboxes.json', $this->getParams(array('fields' => $fields, 'page' => $page)), 'getMailboxes', '\HelpScout\model\Mailbox'
		);
	}

	/**
	 * @param  integer       $mailboxId
	 * @param  integer       $page
	 * @param  string|array  $fields
	 * @return \HelpScout\Collection
	 */
	public function getWorkflows($mailboxId, $page=1, $fields=null) {
		return $this->getCollection(
			sprintf('mailboxes/%d/workflows.json', $mailboxId), $this->getParams(array('fields' => $fields, 'page' => $page)), 'getWorkflows', '\HelpScout\model\Workflow'
		);
	}

	/**
	 * @param  integer $workflowId
	 * @param  integer $conversationId
	 * @return array
	 */
	public function runWorkflow($workflowId, $conversationId) {
		return $this->doPost(sprintf('workflows/%s/conversations/%s.json', $workflowId, $conversationId), false, 200);
	}

	/**
	 * @param  integer $workflowId
	 * @param  array  $conversationIds
	 * @return array
	 */
	public function runWorkflowOnConversations($workflowId, array $conversationIds) {
		$conversationIds = array('conversationIds' => $conversationIds);
		$requestBody = json_encode($conversationIds);
		return $this->doPost(sprintf('workflows/%s/conversations.json', $workflowId), $requestBody, 200);
	}

	/**
	 * @param  string $url
	 * @param  array  $params
	 * @param  string $method
	 * @param  string $model
	 * @return \HelpScout\Collection|boolean
	 */
	private function getCollection($url, $params, $method, $model) {
		list($statusCode, $json) = $this->doGet($url, $params);

		$this->checkStatus($statusCode, $method);

		$json = json_decode($json);
		if ($json) {
			if (isset($params['fields'])) {
				return $json;
			} else {
				return new Collection($json, $model);
			}
		}
		return false;
	}

	/**
	 * @param  string $url
	 * @param  array  $params
	 * @param  string $method
	 * @param  string $model
	 * @return mixed
	 */
	private function getItem($url, $params, $method, $model) {
		list($statusCode, $json) = $this->doGet($url, $params);
		$this->checkStatus($statusCode, $method);

		$json = json_decode($json);
		
		if ($json) {
			if (isset($params['fields']) || !$model) {
				return $json->item;
			} else {
				return new $model($json->item);
			}
		}
		return false;
	}

	/**
	 * @param  integer             $statusCode   The HTTP status code returned
	 * @param  string              $type         The type of request (e.g., GET, POST, etc.)
	 * @param  integer             $expected     The expected HTTP status code
	 * @param  string|array|object $responseBody The returned body
	 * @return void
	 * @throws \HelpScout\ApiException If the expected $statusCode isn't returned
	 */
	private function checkStatus($statusCode, $type, $expected = 200, $responseBody = array()) {
		$expected = (array) $expected;
		$responseBody = $responseBody ?: array();

		if (!in_array($statusCode, $expected)) {
			$exception = new ApiException(
				$this->getErrorMessage($statusCode, $type, $expected, $responseBody),
				$statusCode
			);

			if (array_key_exists('validationErrors', $responseBody)) {
				$exception->setErrors($responseBody['validationErrors']);
			}

			$this->debug(
				$exception->getMessage(),
				'ERROR',
				array(
					'method' => $type,
					'code'   => $exception->getCode(),
					'errors' => $exception->getErrors()
				)
			);

			throw $exception;
		}
	}

	/**
	 * @param integer $statusCode   The HTTP status code returned
	 * @param string  $type         The type of request (e.g., GET, POST, etc.)
	 * @param array   $expected     The expected HTTP status code
	 * @param array   $responseBody The returned body
	 *
	 * @return string
	 */
	private function getErrorMessage($statusCode, $type, array $expected, array $responseBody) {
		$errorKey = array(
			400 => 'The request was not formatted correctly',
			401 => 'Invalid API key',
			402 => 'API key suspended',
			403 => 'Access denied',
			404 => sprintf('Resource not found [%s]', $type),
			405 => 'Invalid method type',
			429 => 'Throttle limit reached. Too many requests',
			500 => 'Application error or server error',
			503 => 'Service Temporarily Unavailable'
		);

		if (array_key_exists('error', $responseBody)) {
			return $responseBody['error'];
		} elseif (array_key_exists($statusCode, $errorKey)) {
			return $errorKey[$statusCode];
		}

		return sprintf(
			'Method %s returned status code %d but we expected code(s) %s',
			$type,
			$statusCode,
			implode(',', $expected)
		);
	}

	/**
	 * @param  array  $params
	 * @param  array  $accepted
	 * @return null|array
	 */
	private function getParams($params=null, array $accepted=array('page','fields','firstName','lastName','email','query','sortField','sortOrder')) {
		if (!$params) {
			return null;
		}
		foreach($params as $key => $val) {
			$key = trim($key);
			if (!in_array($key, $accepted)) {
				unset($params[$key]);
				continue;
			}
			switch($key) {
				case 'fields':
					$val = $this->validateFieldSelectors($val);
					if (empty($val)) {
						unset($params[$key]);
					} else {
						$params[$key] = $val;
					}
					break;
				case 'page':
					$val = intval($val);
					if ($val <= 1) {
						unset($params[$key]);
					}
					break;
				case 'firstName':
					$params[$key] = $val;
					break;
				case 'lastName':
					$params[$key] = $val;
					break;
				case 'email':
					$params[$key] = $val;
					break;
				case 'query':
					$params[$key] = $val;
					break;
				case 'sortField':
					$params[$key] = $val;
					break;
				case 'sortOrder':
					$params[$key] = $val;
					break;
				case 'status':
					if (!in_array($val, array('all','active','pending'))) {
						unset($params[$key]);
					}
					break;
				case 'modifiedSince':
					break;
			}
		}
		if ($params) {
			return $params;
		}
		return null;
	}

	/**
	 * @param  string|array $fields
	 * @return string
	 */
	private function validateFieldSelectors($fields) {
		if (is_string($fields)) {
			$fields = explode(',', $fields);
		}
		if (is_array($fields) && count($fields) > 0) {
			array_walk($fields, create_function('&$val', '$val = trim($val);'));

			$fields = array_filter($fields);
		}

		if ($fields) {
			return implode(',', $fields);
		}
		return $fields;
	}

	/**
	 * @param  string  $location header
	 * @return boolean|string
	 */
	private function getIdFromLocation($location) {
		// Location is in the form of /api/model/5345.json and we extract the
		// id from this location.
		return pathinfo($location, PATHINFO_FILENAME);
	}

    /**
     * @param string $url
     * @param array $params
     * @return array
     * @throws ApiException
     */
	private function doGet($url, $params=null) {
		if (empty($this->apiKey)) {
			$exception = new ApiException('Invalid API Key', 401);
			$this->debug(
				$exception->getMessage(),
				'ERROR',
				array(
					'method' => 'GET',
					'code'   => $exception->getCode(),
					'errors' => $exception->getErrors()
				)
			);
		}

		$this->curl->headers = $this->getDefaultCurlHeaders();
		$this->curl->options = $this->getDefaultCurlOptions();
		$response = $this->curl->get(self::API_URL . $url, $params);
		// Note that doGet doesn't decode here, it returns the actual response
		// body.

		$this->debug('response = ' . $response->body, null, array(
			'method' => 'GET',
			'url'    => $url,
			'params' => $params
		));

		return array($response->headers['Status-Code'], $response->body);
	}

	/**
	 * @param  string   $url
	 * @param  boolean  $requestBody
	 * @param  integer  $expectedCode
	 * @return array
	 * @throws \HelpScout\ApiException If no API key is provided
	 */
	private function doPost($url, $requestBody=false, $expectedCode) {
		if (empty($this->apiKey)) {
			throw new ApiException('Invalid API Key', 401);
		}

		$this->debug('request = ' . $requestBody, null, array(
			'method' => 'POST'
		));

		if ($requestBody !== false) {
			$this->curl->headers = $this->getDefaultCurlHeaders(strlen($requestBody));
		}

		$this->curl->options = $this->getDefaultCurlOptions();
		$response = $this->curl->post(self::API_URL . $url, $requestBody);
		$response->body = json_decode($response->body, true);
		$this->debug('response = ' . json_encode($response->body), null, array(
			'method' => 'POST'
		));

		$this->checkStatus($response->headers['Status-Code'], 'POST', $expectedCode, $response->body);

		return array(
			array_key_exists('Location', $response->headers) 
				? $this->getIdFromLocation($response->headers['Location'])
				: null,
			$response->body
		);
	}

    /**
     * @param string $url
     * @param string $requestBody
     * @param int $expectedCode
     * @return void
     * @throws ApiException
     */
	private function doPut($url, $requestBody, $expectedCode) {
		if (empty($this->apiKey)) {
			throw new ApiException('Invalid API Key', 401);
		}

		$this->debug('request = ' . $requestBody, null, array(
			'method' => 'PUT'
		));

		$this->curl->headers = $this->getDefaultCurlHeaders(strlen($requestBody));
		$this->curl->options = $this->getDefaultCurlOptions();
		$response = $this->curl->put(self::API_URL . $url, $requestBody);

		$this->debug('response = ' . json_encode($response->body), null, array(
			'method' => 'PUT'
		));

		$this->checkStatus($response->headers['Status-Code'], 'PUT', $expectedCode, $response->body);
		return true;
	}

    /**
     * @param string $url
     * @param int $expectedCode
     * @return void
     * @throws ApiException
     */
	private function doDelete($url, $expectedCode) {
		if (empty($this->apiKey)) {
			throw new ApiException('Invalid API Key', 401);
		}

		$this->debug('request = ' . $url, null, array(
			'method' => 'DELETE'
		));

		$this->curl->headers = array();
		$this->curl->options = $this->getDefaultCurlOptions();
		$response = $this->curl->delete(self::API_URL . $url);
		$response->body = json_decode($response->body, true);

		$this->checkStatus($response->headers['Status-Code'], 'DELETE', $expectedCode, $response->body);
		return true;
	}

	/**
	 * @param  string $mesg
	 * @return void
	 */
	private function debug($mesg, $level = 'DEBUG', array $context = array()) {
		if ($this->isDebug == false) return;

		$level = strtoupper($level ?: 'DEBUG');

		$text = strftime('[%b %d %H:%M:%S]') . ' ' . $level . ': ' . $mesg . '; context: ' . json_encode($context) . PHP_EOL;

		if ($this->debugDir) {
			file_put_contents($this->debugDir . DIRECTORY_SEPARATOR . 'apiclient.log', $text, FILE_APPEND);
		} else {
			echo $text;
		}
	}

	private function getDefaultCurlHeaders($contentLength = 0)
	{
		$headers = array(
			'accept'         => 'application/json',
			'content-type'   => 'application/json',
			'expect'		 => ''
		);
		if ($contentLength) {
			$header['content-length'] = $contentLength;
		}
		return $headers;
	}

	private function getDefaultCurlOptions()
	{
		return array(
			'httpauth'       => CURLAUTH_BASIC,
			'userpwd'        => $this->apiKey . ':X',
			'returntransfer' => true,
			'timeout'        => 30,
			'connecttimeout' => 30,
			'ssl_verifypeer' => false,
			'ssl_verifyhost' => 2,
			'header'         => true,
			'encoding'       => 'gzip,deflate',
			'useragent'      => $this->getUserAgent()
		);
	}

	/**
	 * Call a service description if one is available.
	 * 
	 * @param  string $method
	 * @param  array $args
	 * @return mixed
	 * @throws BadMethodCallException
	 */
    public function __call($method, $args)
    {
        if (isset($this->services[$method])) {
            return $this->callServiceDescription($method, $args[0]);
        }

        throw new \BadMethodCallException(sprintf(
        	'The required method "%s" does not exist for %s',
        	$method,
        	get_class($this)
        ));
    }

    /**
     * Construct a call to be made to the API via a Service
     * Description. Do parameter checking. Call the appropriate
     * HTTP verb method (ie: `doGet`).
     * 
     * @param  string $service
     * @param  array $params
     * @return stdObject|array
     */
    private function callServiceDescription($service, $params)
    {
        $service = $this->services[$service];
        $method = 'do' . ucfirst(strtolower($service['httpMethod']));
        $queryParams = array();

        foreach ($service['parameters'] as $param => $paramSettings) {
            $required = isset($paramSettings['required'])
                ? $paramSettings['required']
                : false;

            if ($required && ! isset($params[$param])) {
                throw new \InvalidArgumentException(sprintf(
                    'The %s parameter is required',
                    $param
                ));
            }

            if (isset($params[$param])
                && $paramSettings['location'] === 'query'
            ) {
                $queryParams[$param] = $params[$param];
            }
        }

        list($responseCode, $response) = $this->$method($service['uri'], $queryParams);

        return json_decode($response);
    }

    /**
     * Loop through all stated service description locations and 
     * load their configuration arrays into one merged array.
     * 
     * @return array
     */
    private function loadServiceDescriptions()
    {
        $services = array();

        foreach ($this->serviceDescriptionLocations as $location) {
            $services = array_merge(
                $services,
                require __DIR__ . '/descriptions/' . $location
            );
        }

        return $services;
    }
}
