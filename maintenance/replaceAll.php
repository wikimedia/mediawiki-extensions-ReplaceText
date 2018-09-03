#!/usr/bin/php
<?php
/**
 * Insert jobs into the job queue to replace text bits.
 * Or execute immediately... your choice.
 *
 * Copyright © 2014 NicheWork, LLC
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @category Maintenance
 * @package  ReplaceText
 * @author   Mark A. Hershberger <mah@nichework.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 * @link     https://www.mediawiki.org/wiki/Extension:Replace_Text
 *
 */
// @codingStandardsIgnoreStart
$IP = getenv( "MW_INSTALL_PATH" ) ? getenv( "MW_INSTALL_PATH" ) : __DIR__ . "/../../..";
if ( !is_readable( "$IP/maintenance/Maintenance.php" ) ) {
	die( "MW_INSTALL_PATH needs to be set to your MediaWiki installation.\n" );
}
require_once ( "$IP/maintenance/Maintenance.php" );
// @codingStandardsIgnoreEnd

/**
 * Maintenance script that replaces text in pages
 *
 * @ingroup Maintenance
 * @SuppressWarnings(StaticAccess)
 * @SuppressWarnings(LongVariable)
 */
class ReplaceAll extends Maintenance {
	private $user;
	private $target;
	private $replacement;
	private $namespaces;
	private $category;
	private $prefix;
	private $useRegex;
	private $titles;
	private $defaultContinue;
	private $doAnnounce;

	public function __construct() {
		parent::__construct();
		$this->mDescription = "CLI utility to replace text wherever it is " .
			"found in the wiki.";

		$this->addArg( "target", "Target text to find.", false );
		$this->addArg( "replace", "Text to replace.", false );

		$this->addOption( "dry-run", "Only find the texts, don't replace.",
			false, false, 'n' );
		$this->addOption( "regex", "This is a regex (false).",
			false, false, 'r' );
		$this->addOption( "user", "The user to attribute this to (uid 1).",
			false, true, 'u' );
		$this->addOption( "yes", "Skip all prompts with an assumed 'yes'.",
			false, false, 'y' );
		$this->addOption( "summary", "Alternate edit summary. (%r is where to " .
			" place the replacement text, %f the text to look for.)",
			false, true, 's' );
		$this->addOption( "nsall", "Search all canonical namespaces (false). " .
			"If true, this option overrides the ns option.", false, false, 'a' );
		$this->addOption( "ns", "Comma separated namespaces to search in " .
			"(Main) .", false, true );
		$this->addOption( "replacements", "File containing the list of " .
			"replacements to be made.  Fields in the file are tab-separated. " .
			"See --show-file-format for more information.", false, true, "f" );
		$this->addOption( "show-file-format", "Show a description of the " .
			"file format to use with --replacements.", false, false );
		$this->addOption( "no-announce", "Do not announce edits on Special:RecentChanges or " .
			"watchlists.", false, false, "m" );
		$this->addOption( "debug", "Display replacements being made.", false, false );
		$this->addOption( "listns", "List out the namespaces on this wiki.",
			false, false );

		// MW 1.28
		if ( method_exists( $this, 'requireExtension' ) ) {
			$this->requireExtension( 'Replace Text' );
		}
	}

	private function getUser() {
		$userReplacing = $this->getOption( "user", 1 );

		$user = is_numeric( $userReplacing ) ?
			User::newFromId( $userReplacing ) :
			User::newFromName( $userReplacing );

		if ( get_class( $user ) !== 'User' ) {
			$this->error(
				"Couldn't translate '$userReplacing' to a user.", true
			);
		}

		return $user;
	}

	private function getTarget() {
		$ret = $this->getArg( 0 );
		if ( !$ret ) {
			$this->error( "You have to specify a target.", true );
		}
		return [ $ret ];
	}

	private function getReplacement() {
		$ret = $this->getArg( 1 );
		if ( !$ret ) {
			$this->error( "You have to specify replacement text.", true );
		}
		return [ $ret ];
	}

	private function getReplacements() {
		$file = $this->getOption( "replacements" );
		if ( !$file ) {
			return false;
		}

		if ( !is_readable( $file ) ) {
			throw new MWException( "File does not exist or is not readable: "
				. "$file\n" );
		}

		$handle = fopen( $file, "r" );
		if ( $handle === false ) {
			throw new MWException( "Trouble opening file: $file\n" );
			return false;
		}

		$this->defaultContinue = true;
		// @codingStandardsIgnoreStart
		while ( ( $line = fgets( $handle ) ) !== false ) {
		// @codingStandardsIgnoreEnd
			$field = explode( "\t", substr( $line, 0, -1 ) );
			if ( !isset( $field[1] ) ) {
				continue;
			}

			$this->target[] = $field[0];
			$this->replacement[] = $field[1];
			$this->useRegex[] = isset( $field[2] ) ? true : false;
		}
		return true;
	}

	private function shouldContinueByDefault() {
		if ( !is_bool( $this->defaultContinue ) ) {
			$this->defaultContinue =
				$this->getOption( "yes" ) ?
				true :
				false;
		}
		return $this->defaultContinue;
	}

	private function getSummary( $target, $replacement ) {
		$msg = wfMessage( 'replacetext_editsummary', $target, $replacement )->
			plain();
		if ( $this->getOption( "summary" ) !== null ) {
			$msg = str_replace( [ '%f', '%r' ],
				[ $this->target, $this->replacement ],
				$this->getOption( "summary" ) );
		}
		return $msg;
	}

	private function listNamespaces() {
		echo "Index\tNamespace\n";
		$nsList = MWNamespace::getCanonicalNamespaces();
		ksort( $nsList );
		foreach ( $nsList as $int => $val ) {
			if ( $val == "" ) {
				$val = "(main)";
			}
			echo " $int\t$val\n";
		}
	}

	private function showFileFormat() {
echo <<<EOF

The format of the replacements file is tab separated with three fields.
Any line that does not have a tab is ignored and can be considered a comment.

Fields are:

 1. String to search for.
 2. String to replace found text with.
 3. (optional) The presence of this field indicates that the previous two
	are considered a regular expression.

Example:

This is a comment
TARGET	REPLACE
regex(p*)	Count the Ps; \\1	true


EOF;
	}

	private function getNamespaces() {
		$nsall = $this->getOption( "nsall" );
		$ns = $this->getOption( "ns" );
		if ( !$nsall && !$ns ) {
			$namespaces = [ NS_MAIN ];
		} else {
			$canonical = MWNamespace::getCanonicalNamespaces();
			$canonical[NS_MAIN] = "_";
			$namespaces = array_flip( $canonical );
			if ( !$nsall ) {
				$namespaces = array_map(
					function ( $n ) use ( $canonical, $namespaces ) {
						if ( is_numeric( $n ) ) {
							if ( isset( $canonical[ $n ] ) ) {
								return intval( $n );
							}
						} else {
							if ( isset( $namespaces[ $n ] ) ) {
								return $namespaces[ $n ];
							}
						}
						return null;
					}, explode( ",", $ns ) );
				$namespaces = array_filter(
					$namespaces,
					function ( $val ) {
						return $val !== null;
					} );
			}
		}
		return $namespaces;
	}

	private function getCategory() {
		$cat = null;
		return $cat;
	}

	private function getPrefix() {
		$prefix = null;
		return $prefix;
	}

	private function useRegex() {
		return [ $this->getOption( "regex" ) ];
	}

	private function getTitles( $res ) {
		if ( !$this->titles || count( $this->titles ) == 0 ) {
			$this->titles = [];
			foreach ( $res as $row ) {
				$this->titles[] = Title::makeTitleSafe(
					$row->page_namespace,
					$row->page_title
				);
			}
		}
		return $this->titles;
	}

	private function listTitles( $res ) {
		$ret = false;
		foreach ( $this->getTitles( $res ) as $title ) {
			$ret = true;
			echo "$title\n";
		}
		return $ret;
	}

	private function replaceTitles( $res, $target, $replacement, $useRegex ) {
		foreach ( $this->getTitles( $res ) as $title ) {
			$param = [
				'target_str'      => $target,
				'replacement_str' => $replacement,
				'use_regex'       => $useRegex,
				'user_id'         => $this->user->getId(),
				'edit_summary'    => $this->getSummary( $target, $replacement ),
				'doAnnounce'        => $this->doAnnounce
			];
			echo "Replacing on $title... ";
			$job = new ReplaceTextJob( $title, $param );
			if ( $job->run() !== true ) {
				$this->error( "Trouble on the page '$title'." );
			}
			echo "done.\n";
		}
	}

	private function getReply( $question ) {
		$reply = "";
		if ( $this->shouldContinueByDefault() ) {
			return true;
		}
		while ( $reply !== "y" && $reply !== "n" ) {
			$reply = $this->readconsole( "$question (Y/N) " );
			$reply = substr( strtolower( $reply ), 0, 1 );
		}
		return $reply === "y";
	}

	private function localSetup() {
		if ( $this->getOption( "listns" ) ) {
			$this->listNamespaces();
			return false;
		}
		if ( $this->getOption( "show-file-format" ) ) {
			$this->showFileFormat();
			return false;
		}
		$this->user = $this->getUser();
		if ( ! $this->getReplacements() ) {
			$this->target = $this->getTarget();
			$this->replacement = $this->getReplacement();
			$this->useRegex = $this->useRegex();
		}
		$this->namespaces = $this->getNamespaces();
		$this->category = $this->getCategory();
		$this->prefix = $this->getPrefix();
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function execute() {
		global $wgShowExceptionDetails;
		$wgShowExceptionDetails = true;

		$this->doAnnounce = true;
		if ( $this->localSetup() ) {
			if ( $this->namespaces === [] ) {
				$this->error( "No matching namespaces.", true );
			}

			foreach ( array_keys( $this->target ) as $index ) {
				$target = $this->target[$index];
				$replacement = $this->replacement[$index];
				$useRegex = $this->useRegex[$index];

				if ( $this->getOption( "debug" ) ) {
					echo "Replacing '$target' with '$replacement'";
					if ( $useRegex ) {
						echo " as regular expression.";
					}
					echo "\n";
				}
				$res = ReplaceTextSearch::doSearchQuery( $target,
					$this->namespaces, $this->category, $this->prefix,
					$useRegex );

				if ( $res->numRows() === 0 ) {
					$this->error( "No targets found to replace.", true );
				}
				if ( $this->getOption( "dry-run" ) ) {
					$this->listTitles( $res );
					return;
				}
				if ( !$this->shouldContinueByDefault() &&
					 $this->listTitles( $res ) ) {
					if ( !$this->getReply(
						"Replace instances on these pages?"
					) ) {
						return;
					}
				}
				$comment = "";
				if ( $this->getOption( "user", null ) === null ) {
					$comment = " (Use --user to override)";
				}
				if ( $this->getOption( "no-announce", false ) ) {
					$this->doAnnounce = false;
				}
				if ( !$this->getReply(
					"Attribute changes to the user '{$this->user}'?$comment"
				) ) {
					return;
				}
				if ( $res->numRows() > 0 ) {
					$this->replaceTitles(
						$res, $target, $replacement, $useRegex
					);
				}
			}
		}
	}
}

$maintClass = "ReplaceAll";
require_once RUN_MAINTENANCE_IF_MAIN;
