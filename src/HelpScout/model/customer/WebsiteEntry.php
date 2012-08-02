<?php
namespace HelpScout\model\customer;

class WebsiteEntry extends CustomerEntry {

	public function getType() {
		return 'website';
	}

	public function getLabel() {
		return '';
	}
}