<?php
namespace HelpScout\model;

class Attachment extends Object {	
	private $mimeType;
	private $fileName;
	private $size   = 0;
	private $width  = 0;
	private $height = 0;
	private $url    = false;
	
	private $data   = false;
		
	public function __construct($data=null) {
		parent::__construct($data);
		if ($data) {			
			$this->mimeType = $data->mimeType;
			$this->fileName = $data->fileName;
			$this->size     = $data->size;
			$this->width    = $data->width;
			$this->height   = $data->height;	
			$this->url      = $data->url;						
		}	
	}
	
	public function getData() {
		if ($this->data === false) {
			$this->data = \HelpScout\ApiClient::getInstance()->getAttachmentData($this->id);			
		}
		return $this->data;
	}
	
}