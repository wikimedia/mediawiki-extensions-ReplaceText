<?php

class ReplaceTextSearch {

	/**
	 * @param string $search
	 * @param array $namespaces
	 * @param string $category
	 * @param string $prefix
	 * @param bool $use_regex
	 * @return IResultWrapper Resulting rows
	 */
	public static function doSearchQuery(
		$search, $namespaces, $category, $prefix, $use_regex = false
	) {
		$dbr = wfGetDB( DB_REPLICA );
		$tables = [ 'page', 'revision', 'text' ];
		$vars = [ 'page_id', 'page_namespace', 'page_title', 'old_text' ];
		if ( $use_regex ) {
			$comparisonCond = self::regexCond( $dbr, 'old_text', $search );
		} else {
			$any = $dbr->anyString();
			$comparisonCond = 'old_text ' . $dbr->buildLike( $any, $search, $any );
		}
		$conds = [
			$comparisonCond,
			'page_namespace' => $namespaces,
			'rev_id = page_latest',
			'rev_text_id = old_id'
		];

		self::categoryCondition( $category, $tables, $conds );
		self::prefixCondition( $prefix, $conds );
		$sort = [ 'ORDER BY' => 'page_namespace, page_title' ];

		return $dbr->select( $tables, $vars, $conds, __METHOD__, $sort );
	}

	/**
	 * @param string $category
	 * @param array &$tables
	 * @param array &$conds
	 */
	public static function categoryCondition( $category, &$tables, &$conds ) {
		if ( strval( $category ) !== '' ) {
			$category = Title::newFromText( $category )->getDbKey();
			$tables[] = 'categorylinks';
			$conds[] = 'page_id = cl_from';
			$conds['cl_to'] = $category;
		}
	}

	/**
	 * @param string $prefix
	 * @param array &$conds
	 */
	public static function prefixCondition( $prefix, &$conds ) {
		if ( strval( $prefix ) === '' ) {
			return;
		}

		$dbr = wfGetDB( DB_REPLICA );
		$title = Title::newFromText( $prefix );
		if ( !is_null( $title ) ) {
			$prefix = $title->getDbKey();
		}
		$any = $dbr->anyString();
		$conds[] = 'page_title ' . $dbr->buildLike( $prefix, $any );
	}

	/**
	 * @param \Wikimedia\Rdbms\Database $dbr
	 * @param string $column
	 * @param string $regex
	 * @return string query condition for regex
	 */
	public static function regexCond( $dbr, $column, $regex ) {
		if ( $dbr instanceof DatabasePostgres ) {
			$op = '~';
		} else {
			$op = 'REGEXP';
		}
		return "$column $op " . $dbr->addQuotes( $regex );
	}
}
