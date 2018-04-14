<?php

use MediaWiki\MediaWikiServices;

class ReplaceTextUtils {

	/**
	 * Shim for compatibility
	 * @param Title $title to link to
	 * @param string $text to show
	 * @return string HTML for link
	 */
	public static function link( Title $title, $text = null ) {
		if ( method_exists( 'MediaWikiServices', 'getLinkRenderer' ) ) {
			$linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();
			return $linkRenderer->makeLink( $title, $text );
		};
		return Linker::link( $title, $text );
	}
}
