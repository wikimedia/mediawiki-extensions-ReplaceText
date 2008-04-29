<?php
/**
 * Internationalization file for the Replace Text extension
 *
 * @addtogroup Extensions
*/

$messages = array();

/** English
 * @author Yaron Koren
 */
$messages['en'] = array(
	// user messages
	'replacetext' => 'Replace text',
	'replacetext_docu' => 'To replace one text string with another across all data pages on this wiki, you can enter the two pieces of text here and then hit \'Replace\'. Your name will appear in page histories as the user responsible for the changes.',
	'replacetext_note' => 'Note: this will not replace text in "Talk" pages and project pages, and it will not replace text in page titles themselves.',
	'replacetext_originaltext' => 'Original text',
	'replacetext_replacementtext' => 'Replacement text',
	'replacetext_replace' => 'Replace',
	'replacetext_success' => 'Replaced \'$1\' with \'$2\' in $3 files.',
	'replacetext_noreplacement' => 'No replacements were made; no files were found containing the string \'$1\'.',
	'replacetext_warning' => 'There are $1 files that already contain the replacement string, \'$2\'; if you make this replacement you will not be able to separate your replacements from these strings. Continue with the replacement?',
	'replacetext_blankwarning' => 'Because the replacement string is blank, this operation will not be reversible; continue?',
	'replacetext_continue' => 'Continue',
	'replacetext_cancel' => '(Hit the "Back" button to cancel the operation.)',
	// content messages
	'replacetext_editsummary' => 'Text replace - \'$1\' to \'$2\'',
);
