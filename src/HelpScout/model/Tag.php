<?php
namespace HelpScout\model;

class Tag {
	private $id = false;
	private $slug;
	private $name;
	private $count;
	private $color;
	private $createdAt;
	private $modifiedAt;

	public function __construct($data=null) {
		if ($data) {
			$this->id         = isset($data->id        ) ? $data->id         : null;
			$this->slug       = isset($data->slug      ) ? $data->slug       : null;
			$this->name       = isset($data->name      ) ? $data->name       : null;
			$this->count      = isset($data->count     ) ? $data->count      : null;
			$this->color      = isset($data->color     ) ? $data->color      : null;
			$this->createdAt  = isset($data->createdAt ) ? $data->createdAt  : null;
			$this->modifiedAt = isset($data->modifiedAt) ? $data->modifiedAt : null;
		}
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
	public function getSlug() {
		return $this->slug;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->Name;
	}

	/**
	 * @return int
	 */
	public function getCount() {
		return $this->count;
	}

    /**
     * @return string
     */
    public function getColor() {
        return $this->color;
    }

	/**
	 * @return string
	 */
	public function getCreatedAt() {
		return $this->createdAt;
	}

	/**
	 * @return string
	 */
	public function getModifiedAt() {
		return $this->modifiedAt;
	}

	private function isEmpty($value) {
		$v = trim($value);
		return empty($v);
	}

}
