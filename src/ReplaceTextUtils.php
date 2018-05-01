<?php

use \MediaWiki\MediaWikiServices;

class ReplaceTextUtils {

	/**
	 * Shim for compatibility
	 * @param Title $title to link to
	 * @param string $text to show
	 * @return string HTML for link
	 */
	public static function link( Title $title, $text = null ) {
		if ( method_exists( '\MediaWiki\MediaWikiServices', 'getLinkRenderer' ) ) {
			$linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();
			if ( class_exists( 'HtmlArmor' ) && !is_null( $text ) ) {
				$text = new HtmlArmor( $text );
			}
			return $linkRenderer->makeLink( $title, $text );
		};
		return Linker::link( $title, $text );
	}
}
