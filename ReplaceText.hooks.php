<?php
/**
 */

class ReplaceTextHooks {

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
	 * Adds link to ReplaceText page at the end of successful regular page move message
	 *
	 * @param FormLayout &$form MovePageForm
	 * @param Title &$ot Title object of the old article (moved from)
	 * @param Title &$nt Title object of the new article (moved to)
	 * @return bool
	 */
	public static function replaceTextReminder( &$form, &$ot , &$nt ) {
		$out = $form->getOutput();
		$page = SpecialPageFactory::getPage( 'ReplaceText' );
		$pageLink = Linker::linkKnown( $page->getPageTitle() );
		$out->addHTML( $form->msg( 'replacetext_reminder' )
			->rawParams( $pageLink )->inContentLanguage()->parseAsBlock() );
		return true;
	}
}
