<?php
namespace HelpScout;

class Collection {
	private $page  = 0;
	private $pages = 0;
	private $count = 0;
	private $items = false;

	public function __construct($data, $itemType) {
		if ($data) {
			$this->page  = $data->page;
			$this->pages = $data->pages;
			$this->count = $data->count;

			$items = $data->items;
			if ($items) {
				$this->items = array();

				foreach($items as $index => $item) {
					$this->items[] = new $itemType($item);
					unset($items[$index]);
				}
			}
			unset($data);
		}
	}

	/**
	 * @return boolean
	 */
	public function hasNextPage() {
		return $this->page < $this->pages;
	}

	/**
	 * @return boolean
	 */
	public function hasPrevPage() {
		return $this->page > 1;
	}

	/**
	 * @return array
	 */
	public function getItems() {
		return $this->items;
	}

	/**
	 * @return int
	 */
	public function getPage() {
		return $this->page;
	}

	/**
	 * @return int
	 */
	public function getPages() {
		return $this->pages;
	}

	/**
	 * @return int
	 */
	public function getCount() {
		return $this->count;
	}
}
