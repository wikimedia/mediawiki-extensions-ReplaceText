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
	'replacetext-desc' => 'Provides a special page to allow administrators to do a global string find-and-replace on all the content pages of a wiki',
	'replacetext_docu' => 'To replace one text string with another across all data pages on this wiki, you can enter the two pieces of text here and then hit \'Replace\'. Your name will appear in page histories as the user responsible for the changes.',
	'replacetext_note' => 'Note: this will not replace text in "Talk" pages and project pages, and it will not replace text in page titles themselves.',
	'replacetext_originaltext' => 'Original text',
	'replacetext_replacementtext' => 'Replacement text',
	'replacetext_replace' => 'Replace',
	'replacetext_success' => 'Replaced \'$1\' with \'$2\' in $3 pages.',
	'replacetext_noreplacement' => 'No replacements were made; no pages were found containing the string \'$1\'.',
	'replacetext_warning' => 'There are $1 pages that already contain the replacement string, \'$2\'; if you make this replacement you will not be able to separate your replacements from these strings. Continue with the replacement?',
	'replacetext_blankwarning' => 'Because the replacement string is blank, this operation will not be reversible; continue?',
	'replacetext_continue' => 'Continue',
	'replacetext_cancel' => '(Hit the "Back" button to cancel the operation.)',
	// content messages
	'replacetext_editsummary' => 'Text replace - \'$1\' to \'$2\'',
);

/** French (Français)
 * @author Grondin
 */
$messages['fr'] = array(
	'replacetext'                 => 'Remplacer le texte',
	'replacetext-desc'            => 'Fournit une page spéciale permettant aux administrateurs de remplacer des chaînes de caractères par d’autres sur l’ensemble du wiki',
	'replacetext_docu'            => "Pour remplacer une chaîne de caractères avec une autre sur l'ensemble des données des pages de ce wiki, vous pouvez entrez les deux textes ici et cliquer sur « Remplacer ». Votre nom apparaîtra dans l'historique des pages tel un utilisateur auteur des changements.",
	'replacetext_note'            => 'Note : ceci ne remplacera pas le texte dans les pages de discussion ainsi que dans les pages « projet ». Il ne remplacera pas, non plus, le texte dans le titre lui-même.',
	'replacetext_originaltext'    => 'Texte original',
	'replacetext_replacementtext' => 'Nouveau texte',
	'replacetext_replace'         => 'Remplacer',
	'replacetext_success'         => 'A remplacé « $1 » par « $2 » dans « $3 » fichiers.',
	'replacetext_noreplacement'   => 'Aucun remplacemet n’a été effectué ; aucun fichier contenant la chaîne « $1 » n’a été trouvé.',
	'replacetext_warning'         => 'Il y a $1 fichiers qui contient la chaîne de remplacement « $2 » ; si vous effectuer cette substitution, vous ne pourrez pas séparer vos changements à partir de ces chaînes. Voulez-vous continuez ces substitutions ?',
	'replacetext_blankwarning'    => 'Parce que la chaîne de remplacement est vide, cette opération sera irréversible ; voulez-vous continuer ?',
	'replacetext_continue'        => 'Continuer',
	'replacetext_cancel'          => "(cliquez sur le bouton  « Retour » pour annuler l'opération.)",
	'replacetext_editsummary'     => 'Remplacement du texte — « $1 » par « $2 »',
);

