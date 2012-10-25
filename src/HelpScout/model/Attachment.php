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
			$this->id       = $data->id;
			$this->mimeType = $data->mimeType;
			$this->fileName = $data->fileName;
			$this->size     = $data->size;
			$this->width    = $data->width;
			$this->height   = $data->height;
			$this->url      = $data->url;
            $this->hash     = $data->hash;
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
        return get_object_vars($this);
    }

    public function toJson() {
    	$vars = get_object_vars($this);
    	$vars['data'] = base64_encode($this->data);

  		return json_encode($vars);
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