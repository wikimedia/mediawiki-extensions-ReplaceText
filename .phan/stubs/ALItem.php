<?php

class ALItem {
	public $text;
	public $label;

	static function newFromPage( $page_name_or_title, $desc = null, $query = array() ) {}
	static function newFromSpecialPage( $page_name ) {}
	static function newFromEditLink( $page_name, $desc ) {}
	static function newFromExternalLink( $url, $label ) {}
}
