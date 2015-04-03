<?php
namespace HelpScout\model;

class Attachment {
	private $id;
	private $mimeType;
	private $fileName;
	private $size   = 0;
	private $width  = 0;
	private $height = 0;
	private $url;
	private $hash;

	private $data;

	public function __construct($data=null) {
		if ($data) {
			$this->id       = isset($data->id)       ? $data->id       : null;
			$this->mimeType = isset($data->mimeType) ? $data->mimeType : null;
			$this->fileName = isset($data->fileName) ? $data->fileName : null;
			$this->size     = isset($data->size)     ? $data->size     : 0;
			$this->width    = isset($data->width)    ? $data->width    : 0;
			$this->height   = isset($data->height)   ? $data->height   : 0;
			$this->url      = isset($data->url)      ? $data->url      : null;
			$this->hash     = isset($data->hash)     ? $data->hash     : null;
		}
	}

	public function load($filename) {
		if (file_exists($filename)) {
			$this->data     = file_get_contents($filename);
			$this->fileName = basename($filename);
			$this->mimeType = mime_content_type($filename);
			$this->size     = filesize($filename);

			if (substr($this->mimeType, 0, 5) == 'image') {
				$width  = 0;
				$height = 0;

				$info = getimagesize($filename);
				if ($info) {
					if (isset($info[0])) {
						$width = $info[0];
					}
					if (isset($info[1])) {
						$height = $info[1];
					}
				}
				$this->width  = $width;
				$this->height = $height;
			}
		}
	}
	public function getObjectVars() {
        $vars = get_object_vars($this);

        if (isset($vars['hash']) && !empty($vars['hash'])) {
            unset($vars['data']);
        } else {
            $vars['data'] = base64_encode($this->data);
        }
        return $vars;
	}

	public function toJson() {
		return json_encode($this->getObjectVars());
	}

	/**
	 * @param $data
	 */
	public function setData($data) {
		$this->data = $data;
	}

	/**
	 * Returns the raw data for this attachment.
	 *
	 * @return string
	 */
	public function getData() {
		if ($this->data === false) {
			$this->data = \HelpScout\ApiClient::getInstance()->getAttachmentData($this->id);
		}
		return $this->data;
	}

	/**
	 * @param $fileName
	 */
	public function setFileName($fileName) {
		$this->fileName = $fileName;
	}

	/**
	 * @param $mimeType
	 */
	public function setMimeType($mimeType) {
		$this->mimeType = $mimeType;
	}

	/**
	 * @param $hash
	 */
	public function setHash($hash) {
		$this->hash = $hash;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getMimeType() {
		return $this->mimeType;
	}

	/**
	 * @return string
	 */
	public function getFileName() {
		return $this->fileName;
	}

	/**
	 * @return int
	 */
	public function getSize() {
		return $this->size;
	}

	/**
	 * @return int
	 */
	public function getWidth() {
		return $this->width;
	}

	/**
	 * @return int
	 */
	public function getHeight() {
		return $this->height;
	}

	/**
	 * @return string
	 */
	public function getUrl() {
		return $this->url;
	}
}
