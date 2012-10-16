<?php
namespace HelpScout\model;

class Attachment {	
	private $id = false;
	private $mimeType;
	private $fileName;
	private $size   = 0;
	private $width  = 0;
	private $height = 0;
	private $url    = false;
	
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
		}	
	}

    public function getObjectVars() {
        return get_object_vars($this);
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