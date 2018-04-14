<?php
/**
 * Hook functions for the Replace Text extension.
 */

class ReplaceTextHooks {

	/**
	 * Implements AdminLinks hook from Extension:Admin_Links.
	 *
	 * @param ALTree &$adminLinksTree
	 * @return bool
	 */
	public static function addToAdminLinks( ALTree &$adminLinksTree ) {
		$generalSection = $adminLinksTree->getSection( wfMessage( 'adminlinks_general' )->text() );
		$extensionsRow = $generalSection->getRow( 'extensions' );

		if ( is_null( $extensionsRow ) ) {
			$extensionsRow = new ALRow( 'extensions' );
			$generalSection->addRow( $extensionsRow );
		}

		$extensionsRow->addItem( ALItem::newFromSpecialPage( 'ReplaceText' ) );

		return true;
	}

	/**
	 * Implements SpecialMovepageAfterMove hook.
	 *
	 * Adds a link to the Special:ReplaceText page at the end of a successful
	 * regular page move message.
	 *
	 * @param FormLayout &$form MovePageForm
	 * @param Title &$ot Title object of the old article (moved from)
	 * @param Title &$nt Title object of the new article (moved to)
	 */
	public static function replaceTextReminder( &$form, &$ot, &$nt ) {
		$out = $form->getOutput();
		$page = SpecialPageFactory::getPage( 'ReplaceText' );
		$pageLink = ReplaceTextUtils::link( $page->getPageTitle() );
		$out->addHTML( $form->msg( 'replacetext_reminder' )
			->rawParams( $pageLink )->inContentLanguage()->parseAsBlock() );
	}

	/**
	 * Implements UserGetReservedNames hook.
	 * @param array &$names
	 */
	public static function getReservedNames( &$names ) {
		global $wgReplaceTextUser;
		if ( !is_null( $wgReplaceTextUser ) ) {
			$names[] = $wgReplaceTextUser;
		}
	}
}
