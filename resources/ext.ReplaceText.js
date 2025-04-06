( function () {
	'use strict';

	function invertSelections() {
		var form = document.getElementById( 'choose_pages' ),
			numElements = form.elements.length,
			i,
			curElement;

		for ( i = 0; i < numElements; i++ ) {
			curElement = form.elements[ i ];

			if ( curElement.type === 'checkbox' && curElement.id !== 'create-redirect' &&
				curElement.id !== 'watch-pages' && curElement.id !== 'botEdit' ) {
				curElement.checked = !curElement.checked;
			}
		}
	}

	/**
	 * Add a visible codepoint (character) limit label to a TextInputWidget.
	 *
	 * Uses jQuery#codePointLimit to enforce the limit.
	 *
	 * @param {OO.ui.TextInputWidget} textInputWidget Text input widget
	 * @param {number} [limit] Code point limit, defaults to $input's maxlength
	 * @param {Function} [filterFunction] Function to call on the string before assessing the length.
	 */
	var visibleCodePointLimit = function ( textInputWidget ) {
		var limit = +textInputWidget.$input.attr( 'maxlength' );
		var codePointLength = require( 'mediawiki.String' ).codePointLength;

		function updateCount() {
			var value = textInputWidget.getValue(),
				remaining;
			remaining = limit - codePointLength( value );
			if ( remaining > 99 ) {
				remaining = '';
			} else {
				remaining = mw.language.convertNumber( remaining );
			}
			textInputWidget.setLabel( remaining );
		}
		textInputWidget.on( 'change', updateCount );
		// Initialise value
		updateCount();
	};

	$( function () {
		var $checkboxes = $( '#powersearch input[id^="mw-search-ns"]' );

		$( '.ext-replacetext-invert' ).on( 'click', invertSelections );

		// Attach handler for check all/none buttons
		$( '#mw-search-toggleall' ).on( 'click', function () {
			$checkboxes.prop( 'checked', true );
		} );
		$( '#mw-search-togglenone' ).on( 'click', function () {
			$checkboxes.prop( 'checked', false );
		} );

		var $wpSummary = $( '#wpSummary' );
		if ( $wpSummary.length ) {
			// Show a byte-counter to users with how many bytes are left for their edit summary.
			visibleCodePointLimit( OO.ui.infuse( $wpSummary ) );
		}
	} );
}() );
