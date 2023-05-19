<?php
declare( strict_types = 1 );

namespace MediaWiki\Extension\ReplaceText;

use MediaWiki\HookContainer\HookContainer;
use Title;
use TitleArrayFromResult;
use Wikimedia\Rdbms\IResultWrapper;

class HookRunner {
	private HookContainer $hookContainer;

	public function __construct( HookContainer $hookContainer ) {
		$this->hookContainer = $hookContainer;
	}

	/**
	 * Runs the ReplaceTextFilterPageTitlesForEdit hook and returns titles to be edited
	 * @param IResultWrapper $resultWrapper
	 * @return Title[]
	 */
	public function filterPageTitlesForEdit( IResultWrapper $resultWrapper ): array {
		$titles = new TitleArrayFromResult( $resultWrapper );
		$filteredTitles = iterator_to_array( $titles );
		$this->hookContainer->run( 'ReplaceTextFilterPageTitlesForEdit', [ &$filteredTitles ] );

		foreach ( $filteredTitles as $title ) {
			$filteredTitles[ $title->getPrefixedText() ] = $title;
		}

		$titlesToEdit = [];
		foreach ( $titles as $title ) {
			if ( isset( $filteredTitles[ $title->getPrefixedText() ] ) ) {
				$titlesToEdit[ $title->getPrefixedText() ] = $title;
			} else {
				$titlesToEdit[ $title->getPrefixedText() ] = null;
			}
		}

		return $titlesToEdit;
	}
}
