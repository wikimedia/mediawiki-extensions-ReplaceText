<?php
/**
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
 * https://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @author Yaron Koren
 * @author Ankit Garg
 */
namespace MediaWiki\Extension\ReplaceText;

use MediaWiki\CommentStore\CommentStoreComment;
use MediaWiki\Content\ContentHandler;
use MediaWiki\Content\TextContent;
use MediaWiki\Context\RequestContext;
use MediaWiki\JobQueue\Job as JobParent;
use MediaWiki\Page\MovePageFactory;
use MediaWiki\Page\WikiPageFactory;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Title\Title;
use MediaWiki\User\UserFactory;
use MediaWiki\Watchlist\WatchlistManager;
use Wikimedia\ScopedCallback;

/**
 * Background job to replace text in a given page
 * - based on /includes/RefreshLinksJob.php
 */
class Job extends JobParent {
	/**
	 * @param Title $title
	 * @param array|bool $params Cannot be === true
	 * @param MovePageFactory $movePageFactory
	 * @param PermissionManager $permissionManager
	 * @param UserFactory $userFactory
	 * @param WatchlistManager $watchlistManager
	 * @param WikiPageFactory $wikiPageFactory
	 */
	public function __construct( $title, $params,
		private readonly MovePageFactory $movePageFactory,
		private readonly PermissionManager $permissionManager,
		private readonly UserFactory $userFactory,
		private readonly WatchlistManager $watchlistManager,
		private readonly WikiPageFactory $wikiPageFactory,
	) {
		parent::__construct( 'replaceText', $title, $params );
	}

	/**
	 * Run a replaceText job
	 * @return bool success
	 */
	public function run(): bool {
		// T279090
		$current_user = $this->userFactory->newFromId( $this->params['user_id'] );
		if ( !$current_user->isRegistered() ) {
			$this->error = 'replacetext: the user ID ' . $this->params['user_id'] .
				' does not belong to a registered user.';
			return false;
		}
		if ( !$this->permissionManager->userCan(
			'replacetext', $current_user, $this->title
		) ) {
			$this->error = 'replacetext: permission no longer valid';
			// T279090#6978214
			return true;
		}

		if ( isset( $this->params['session'] ) ) {
			$callback = RequestContext::importScopedSession( $this->params['session'] );
			$this->addTeardownCallback( static function () use ( &$callback ) {
				ScopedCallback::consume( $callback );
			} );
		}

		if ( $this->title === null ) {
			$this->error = 'replaceText: Invalid title';
			return false;
		}

		if ( array_key_exists( 'move_page', $this->params ) ) {
			$new_title = Search::getReplacedTitle(
				$this->title,
				$this->params['target_str'],
				$this->params['replacement_str'],
				$this->params['use_regex']
			);

			if ( $new_title === null ) {
				$this->error = 'replaceText: Invalid new title - ' . $this->params['replacement_str'];
				return false;
			}

			$reason = $this->params['edit_summary'];
			$create_redirect = $this->params['create_redirect'];
			$mvPage = $this->movePageFactory->newMovePage( $this->title, $new_title );
			$mvStatus = $mvPage->move( $current_user, $reason, $create_redirect );
			if ( !$mvStatus->isOK() ) {
				$this->error = 'replaceText: error while moving: ' . $this->title->getPrefixedDBkey() .
					'. Errors: ' . $mvStatus->getWikiText();
				return false;
			}

			if ( $this->params['watch_page'] ) {
				$this->watchlistManager->addWatch( $current_user, $new_title );
			}
		} else {
			$wikiPage = $this->wikiPageFactory->newFromTitle( $this->title );
			$latestRevision = $wikiPage->getRevisionRecord();

			if ( $latestRevision === null ) {
				$this->error =
					'replaceText: No revision found for wiki page at "' . $this->title->getPrefixedDBkey() . '".';
				return false;
			}

			if ( isset( $this->params['roles'] ) ) {
				$slotRoles = $this->params['roles'];
			} else {
				$slotRoles = $latestRevision->getSlotRoles();
			}

			$revisionSlots = $latestRevision->getSlots();
			$updater = $wikiPage->newPageUpdater( $current_user );
			$hasMatches = false;

			foreach ( $slotRoles as $role ) {
				if ( !$revisionSlots->hasSlot( $role ) ) {
					$this->error =
						'replaceText: Slot "' . $role .
						'" does not exist for wiki page "' . $this->title->getPrefixedDBkey() . '".';
					return false;
				}

				$slotContent = $revisionSlots->getContent( $role );

				if ( !( $slotContent instanceof TextContent ) ) {
					// Sanity check: Does the slot actually contain TextContent?
					$this->error =
						'replaceText: Slot "' . $role .
						'" does not hold regular wikitext for wiki page "' . $this->title->getPrefixedDBkey() . '".';
					return false;
				}

				// Sometimes, especially if the system is
				// overloaded, the getText() call can
				// lead to this error message getting placed
				// right within the text - if that happens,
				// try calling getText() again until the
				// error goes away.
				$expansionDepthLimitError = '<span class="error">Expansion depth limit exceeded</span>';
				$numTries = 0;
				do {
					$slot_text = $slotContent->getText();
					$hasError = strpos( $slot_text, $expansionDepthLimitError ) > 0;
					$numTries++;
				} while ( $hasError && $numTries < 10 );

				if ( $hasError ) {
					$this->error =
						'replaceText: Encountered "Expansion depth limit exceeded"' .
						' error for wiki page "' . $this->title->getPrefixedDBkey() . '".';
					return false;
				}

				$target_str = $this->params['target_str'];
				$replacement_str = $this->params['replacement_str'];
				$num_matches = 0;

				if ( $this->params['use_regex'] ) {
					$new_text =
						preg_replace( '/' . $target_str . '/Uu', $replacement_str, $slot_text, -1, $num_matches );
				} else {
					$new_text = str_replace( $target_str, $replacement_str, $slot_text, $num_matches );
				}

				// If there's at least one replacement, modify the slot.
				if ( $num_matches > 0 ) {
					$hasMatches = true;
					$updater->setContent(
						$role,
						ContentHandler::makeContent( $new_text, $this->title, $slotContent->getModel() )
					);
				}
			}

			// If at least one slot is edited, modify the page,
			// using the passed-in edit summary.
			if ( $hasMatches ) {
				$edit_summary = CommentStoreComment::newUnsavedComment( $this->params['edit_summary'] );
				$flags = EDIT_MINOR;
				if ( $this->permissionManager->userHasRight( $current_user, 'bot' ) ) {
					$flags |= EDIT_FORCE_BOT;
				}
				if ( isset( $this->params['botEdit'] ) && $this->params['botEdit'] ) {
					$flags |= EDIT_FORCE_BOT;
				}
				if ( $this->permissionManager->userHasRight( $current_user, 'patrol' ) ||
					$this->permissionManager->userHasRight( $current_user, 'autopatrol' ) ) {
					$updater->setRcPatrolStatus( RecentChange::PRC_PATROLLED );
				}
				$updater->saveRevision( $edit_summary, $flags );
			}
		}
		return true;
	}
}
