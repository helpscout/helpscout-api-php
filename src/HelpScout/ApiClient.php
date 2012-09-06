<?php
namespace HelpScout;

require 'ClassLoader.php';

final class ApiClient {
	const API_URL = 'https://api.helpscout.net/v1/';

	const NAMESPACE_SEPARATOR = '\\';

	private $apiKey  = false;

	/**
	 * @var \HelpScout\ApiClient
	 */
	private static $instance = false;

	private function __construct() {
		\HelpScout\ClassLoader::register();
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
	 * Set the API Key to use with this request
	 *
	 * @param string $apiKey
	 */
	public function setKey($apiKey) {
		$this->apiKey = $apiKey;
	}

	/**
	 * Get a list of conversation for the given folder
	 *
	 * @param int $mailboxId
	 * @param int $folderId
	 * @param array $params
	 * @param array|string $fields
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
	 * @param int $mailboxId
	 * @param array $params
	 * @param array $fields
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
	 * @param int $mailboxId
	 * @param int $customerId
	 * @param array $params
	 * @param array $fields
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
	 * @param int $conversationId
	 * @param string|array $fields
	 * @return \HelpScout\model\Conversation
	 */
	public function getConversation($conversationId, $fields=null) {
		if (!is_numeric($conversationId) || $conversationId < 1) {
			throw new ApiException(sprintf('Invalid conversationId in getConversation method [%s]', $conversationId));
		}
		return $this->getItem(
			sprintf('conversations/%d.json', $conversationId), $this->getParams(array('fields' => $fields)), 'getConversation', '\HelpScout\model\Conversation'
		);
	}

	/**
	 * @param int $attachmentId
	 * @return string
	 */
	public function getAttachmentData($attachmentId) {
		if (!is_numeric($attachmentId) || $attachmentId < 1) {
			throw new ApiException(sprintf('Invalid attachmentId in getAttachmentData method [%s]', attachmentId));
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
		return $this->getParams(array_merge($params, array('fields' => $fields)), array('page','fields','status','modifiedSince'));
	}

	/**
	 * Returns a Collection of all the users for the company.
	 * @param int $page
	 * @param string|array $fields
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
	 * @param int $page
	 * @param string|array $fields
	 * @return \HelpScout\Collection
	 */
	public function getUsersForMailbox($mailboxId, $page=1, $fields=null) {
		return $this->getCollection(
			sprintf('mailboxes/%d/users.json', $mailboxId), $this->getParams(array('fields' => $fields, 'page' => $page)), 'getUsersForMailbox', '\HelpScout\model\User'
		);
	}

	/**
	 * @param int $userId
	 * @param string|array $fields
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
	 * Returns a Collection of all the customers for the company.
	 * @param int $page
	 * @param string|array $fields
	 * @return \HelpScout\Collection
	 */
	public function getCustomers($page=1, $fields=null) {
		return $this->getCollection(
			'customers.json', $this->getParams(array('fields' => $fields, 'page' => $page)), 'getCustomers', '\HelpScout\model\Customer'
		);
	}

	/**
	 * @param int $customerId
	 * @param string|array $fields
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
	 * @param int $mailboxId
	 * @param int $page
	 * @param string|array $fields
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
	 * @param int $mailboxId
	 * @param string|array $fields
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
	 * Get a list of Mailboxes for the given user
	 * @param int $page
	 * @param string|array $fields
	 * @return \HelpScout\Collection
	 */
	public function getMailboxes($page=1, $fields=null) {
		return $this->getCollection(
			'mailboxes.json', $this->getParams(array('fields' => $fields, 'page' => $page)), 'getMailboxes', '\HelpScout\model\Mailbox'
		);
	}

	private function getCollection($url, $params, $method, $model) {
		list($statusCode, $json) = $this->callServer($url, 'GET', $params);

		$this->checkStatus($statusCode, $method);

		$json = json_decode($json);
		if ($json) {
			if (isset($params['fields'])) {
				return $json;
			} else {
				return new \HelpScout\Collection($json, $model);
			}
		}
		return false;
	}

	private function getItem($url, $params, $method, $model) {
		list($statusCode, $json) = $this->callServer($url, 'GET', $params);
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

	private function checkStatus($statusCode, $type, $expected = 200) {
		if (!is_array($expected)) {
			$expected = array($expected);
		}

		if (!in_array($statusCode, $expected)) {
			switch($statusCode) {
				case 400:
					throw new ApiException('The request was not formatted correctly', 400);
					break;
				case 401:
					throw new ApiException('Invalid API key', 401);
					break;
				case 402:
					throw new ApiException('API key suspended', 402);
					break;
				case 403:
					throw new ApiException('Access denied', 403);
					break;
				case 404:
					throw new ApiException(sprintf('Resource not found [%s]', $type), 404);
					break;
				case 405:
					throw new ApiException('Invalid method type', 405);
					break;
				case 429:
					throw new ApiException('Throttle limit reached. Too many requests', 429);
					break;
				case 500:
					throw new ApiException('Application error or server error', 500);
					break;
				case 503:
					throw new ApiException('Service Temporarily Unavailable', 503);
					break;
				default:
					throw new ApiException(sprintf('Method %s returned status code %d but we expected code(s) %s', $type, $statusCode, implode(',', $expected)));
					break;
			}
		}
	}

	private function getParams($params=null, array $accepted=array('page','fields')) {
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

	private function callServer($url, $method='GET', $params=null) {
		if ($this->apiKey === false || empty($this->apiKey)) {
			throw new ApiException('Invalid API Key', 401);
		}

		$ch = curl_init();
		$opts = array(
			CURLOPT_URL			   => self::API_URL . $url,
			CURLOPT_CUSTOMREQUEST  => $method,
			CURLOPT_HTTPHEADER     => array(
				'Accept: application/json',
				'Content-Type: application/json'
			),
			CURLOPT_HTTPAUTH       => CURLAUTH_BASIC,
			CURLOPT_USERPWD		   => $this->apiKey . ':X',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT        => 30,
			CURLOPT_CONNECTTIMEOUT => 30,
			CURLOPT_FAILONERROR    => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => true,
			CURLOPT_HEADER         => false,
			CURLOPT_ENCODING       => 'gzip,deflate',
			CURLOPT_USERAGENT      => 'Help Scout API/Php Client v1'
		);
		if ($params) {
			if ($method=='GET') {
				$opts[CURLOPT_URL] = self::API_URL . $url . '?' . http_build_query($params);
			} else {
				$opts[CURLOPT_POSTFIELDS] = $params;
			}
		}
		curl_setopt_array($ch, $opts);

		$response   = curl_exec($ch);
		$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		curl_close($ch);

		return array($statusCode, $response);
	}
}
