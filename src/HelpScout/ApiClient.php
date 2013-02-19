<?php
namespace HelpScout;

require 'ClassLoader.php';

final class ApiClient {
	const USER_AGENT = 'Help Scout API/Php Client v1';
	const API_URL = 'https://api.helpscout.net/v1/';
	const NAMESPACE_SEPARATOR = '\\';

	private $userAgent = false;
	private $apiKey    = false;
	private $isDebug   = false;
	private $debugDir  = false;

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
	 * Put ApiClient in debug mode or note. If in debug mode, you can optionally supply a directory in which to write debug messages.
	 * If no directory is set, debug messages are echo'ed out.
	 * @param boolean $bool
	 * @param string $dir
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

	public function setUserAgent($userAgent) {
		$userAgent = trim($userAgent);
		if (!empty($userAgent)) {
			$this->userAgent = $userAgent;
		}
	}

	private function getUserAgent() {
		if ($this->userAgent) {
			return $this->userAgent;
		}
		return self::USER_AGENT;
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
     * @throws ApiException
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
     * @throws ApiException
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
     * @param $mailboxId
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
     * @throws ApiException
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

    public function getCustomersForMailbox($mailboxId, $page=1, $fields=null) {
        return $this->getCollection(
            sprintf('mailboxes/%d/customers.json', $mailboxId), $this->getParams(array('fields' => $fields, 'page' => $page)), 'getCustomersForMailbox', '\HelpScout\model\Customer'
        );
    }

    /**
     * @param int $customerId
     * @param string|array $fields
     * @throws ApiException
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
	 * @param string $email
	 * @param int $page
	 * @param array $fields
	 * @return \HelpScout\Collection
	 */
	public function searchCustomersByEmail($email, $page=1, $fields=null) {
		$params = array('fields' => $fields, 'page' => $page, 'email' => $email);
		return $this->getCollection('customers.json', $this->getParams($params), 'searchCustomers', '\HelpScout\model\Customer');
	}

	/**
	 * @param string $firstName
	 * @param string $lastName
	 * @param int $page
	 * @param array $fields
	 * @return \HelpScout\Collection
	 */
    public function searchCustomersByName($firstName, $lastName, $page=1, $fields=null) {
        $params = array('fields' => $fields, 'page' => $page, 'firstName' => $firstName, 'lastName' => $lastName);
        return $this->getCollection('customers.json', $this->getParams($params), 'searchCustomers', '\HelpScout\model\Customer');
    }

    /**
     * @param string $email
     * @param int $page
     * @param array $fields
     * @return \HelpScout\Collection
     */
    public function searchCustomers($firstName=null, $lastName=null, $email=null, $page=1, $fields=null) {
        $params = array('fields' => $fields, 'page' => $page, 'firstName' => $firstName, 'lastName' => $lastName, 'email' => $email);
        return $this->getCollection("customers.json", $this->getParams($params), 'searchCustomers', '\HelpScout\model\Customer');
    }

    /**
     * @param model\Conversation $conversation
     * @return bool|string
     */
    public function createConversation(\HelpScout\model\Conversation $conversation, $imported=false) {
        $url = 'conversations.json';
        if ($imported) {
            $url = $url . '?imported=true';
        }
        $json = $conversation->toJSON();
        list($id, ) = $this->doPost($url, $json, 201);
        $conversation->setId($id);
    }

    /**
     * @param $conversationId
     * @param model\thread\ConversationThread $thread
     */
    public function createThread($conversationId, \HelpScout\model\thread\ConversationThread $thread, $imported=false) {
        $url = 'conversations/' . $conversationId . '.json';
        if ($imported) {
            $url = $url . '?imported=true';
        }
        list($id, ) = $this->doPost($url, $thread->toJson(), 201);
        $thread->setId($id);
    }

    public function createAttachment(\HelpScout\model\Attachment $attachment) {
        list($id, $body) = $this->doPost('attachments.json', $attachment->toJson(), 201);

        if ($body) {
        	$body = json_decode($body);
        	$attachment->setHash($body->item->hash);
        }
    }

    public function deleteAttachment($id) {
        $this->doDelete('attachments/' . $id . '.json', 200);
    }

    /**
     * @param model\Conversation $conversation
     */
    public function updateConversation(\HelpScout\model\Conversation $conversation) {
        $this->doPut('conversations/' . $conversation->getId() . '.json', $conversation->toJSON(), 200);
    }

    public function deleteConversation($id) {
        $this->doDelete('conversations/' . $id . '.json', 200);
    }

    /**
     * @param model\Customer $customer
     */
    public function createCustomer(\HelpScout\model\Customer $customer) {
        list($id, ) = $this->doPost('customers.json', $customer->toJSON(), 201);
        $customer->setId($id);
    }

    public function updateCustomer(\HelpScout\model\Customer $customer) {
        $this->doPut('customers/' . $customer->getId() . '.json', $customer->toJSON(), 200);
    }

    /**
     * @param int $mailboxId
     * @param int $page
     * @param string|array $fields
     * @throws ApiException
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
     * @throws ApiException
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
	 * @param int $mailboxId
	 * @return \HelpScout\model\ref\MailboxRef
	 */
	public function getMailboxProxy($mailboxId) {
		$ref = new \HelpScout\model\ref\MailboxRef();
		$ref->setId($mailboxId);
		return $ref;
	}

	/**
	 * Returns a MailboxRef object initialized with the given id.
	 *
	 * @param int $userId
	 * @return \HelpScout\model\ref\UserRef
	 */
	public function getUserRefProxy($userId) {
		$ref = new \HelpScout\model\ref\UserRef();
		$ref->setId($userId);
		return $ref;
	}

	/**
	 * Returns a CustomerRef object initialized with the given HelpScout
	 * Customer ID and email (optional)
	 *
	 * @param int $customerId
	 * @param int $customerEmail
	 * @return \HelpScout\model\ref\CustomerRef
	 */
	public function getCustomerRefProxy($customerId, $customerEmail = false) {
		$ref = new \HelpScout\model\ref\CustomerRef();
		$ref->setId($customerId);
		if ( $customerEmail ) $ref->setEmail($customerEmail);
		return $ref;
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

	private function getParams($params=null, array $accepted=array('page','fields','firstName','lastName','email')) {
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

    private function doPost($url, $requestBody, $expectedCode) {
        if ($this->apiKey === false || empty($this->apiKey)) {
            throw new ApiException('Invalid API Key', 401);
        }

        if ($this->isDebug) {
        	$this->debug($requestBody);
        }
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL            => self::API_URL . $url,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_HTTPHEADER     => array(
                'Accept: application/json',
                'Content-Type: application/json',
                'Content-Length: ' . strlen($requestBody)
            ),
            CURLOPT_POSTFIELDS     => $requestBody,
            CURLOPT_HTTPAUTH       => CURLAUTH_BASIC,
            CURLOPT_USERPWD		   => $this->apiKey . ':X',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_FAILONERROR    => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => true,
            CURLOPT_HEADER         => true,
            CURLOPT_ENCODING       => 'gzip,deflate',
            CURLOPT_USERAGENT      => $this->getUserAgent()
        ));

        $response = curl_exec($ch);
        $info = curl_getinfo($ch);

        curl_close($ch);

        $this->checkStatus($info['http_code'], 'POST', $expectedCode);

        return array($this->getIdFromLocation($response, $info['header_size']), substr($response, $info['header_size']));
    }

    private function getIdFromLocation($response, $headerSize) {
    	$location = false;
    	$headerText = substr($response, 0, $headerSize);
    	$headerLines = explode("\r\n", $headerText);

    	foreach($headerLines as $line) {
    		$parts = explode(': ',$line);
    		if (strtolower($parts[0]) == 'location') {
    			$location = chop($parts[1]);
    			break;
    		}
    	}

    	$id = false;
    	if ($location) {
    		$start = strrpos($location, '/') + 1;
    		$id = substr($location, $start, -5);
    	}
		return $id;
    }

    private function doPut($url, $requestBody, $expectedCode) {
        if ($this->apiKey === false || empty($this->apiKey)) {
            throw new ApiException('Invalid API Key', 401);
        }
        if ($this->isDebug) {
        	$this->debug($requestBody);
        }

        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL            => self::API_URL . $url,
            CURLOPT_CUSTOMREQUEST  => 'PUT',
            CURLOPT_HTTPHEADER     => array(
                'Accept: application/json',
                'Content-Type: application/json',
                'Content-Length: ' . strlen($requestBody)
            ),
            CURLOPT_POSTFIELDS     => $requestBody,
            CURLOPT_HTTPAUTH       => CURLAUTH_BASIC,
            CURLOPT_USERPWD		   => $this->apiKey . ':X',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_FAILONERROR    => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => true,
            CURLOPT_HEADER         => true,
            CURLOPT_ENCODING       => 'gzip,deflate',
            CURLOPT_USERAGENT      => $this->getUserAgent()
        ));

        $response = curl_exec($ch);
        $info = curl_getinfo($ch);

        curl_close($ch);

        $this->checkStatus($info['http_code'], 'PUT', $expectedCode);
    }

    private function doDelete($url, $expectedCode) {
        if ($this->apiKey === false || empty($this->apiKey)) {
            throw new ApiException('Invalid API Key', 401);
        }

        if ($this->isDebug) {
        	$this->debug($url);
        }
        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL            => self::API_URL . $url,
            CURLOPT_CUSTOMREQUEST  => 'DELETE',
            CURLOPT_HTTPAUTH       => CURLAUTH_BASIC,
            CURLOPT_USERPWD		   => $this->apiKey . ':X',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_FAILONERROR    => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => true,
            CURLOPT_HEADER         => true,
            CURLOPT_ENCODING       => 'gzip,deflate',
            CURLOPT_USERAGENT      => $this->getUserAgent()
        ));

        $response = curl_exec($ch);
        $info = curl_getinfo($ch);

        curl_close($ch);

        $this->checkStatus($info['http_code'], 'DELETE', $expectedCode);
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
			CURLOPT_USERAGENT      => $this->getUserAgent()
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

	private function debug($mesg) {
		$text = strftime('%b %d %H:%M:%S') . ': ' . $mesg . PHP_EOL;

		if ($this->debugDir) {
			file_put_contents($this->debugDir . DIRECTORY_SEPARATOR . 'apiclient.log', $text, FILE_APPEND);
		} else {
			echo $text;
		}
	}
}
