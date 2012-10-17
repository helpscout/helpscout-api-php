<?php
namespace HelpScout\model;

class Attachment {	
	private $id;
	private $mimeType;
	private $fileName;
	private $size   = 0;
	private $width  = 0;
	private $height = 0;
	private $url    = false;
    private $hash;
	
	private $data   = false;
		
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

    public function getObjectVars() {
        return get_object_vars($this);
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