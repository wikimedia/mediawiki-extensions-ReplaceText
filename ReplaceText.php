<?php
/**
 * Replace Text - a MediaWiki extension that provides a special page to
 * allow administrators to do a global string find-and-replace on all the
 * content pages of a wiki.
 *
 * http://www.mediawiki.org/wiki/Extension:Text_Replace
 *
 * The special page created is 'Special:ReplaceText', and it provides
 * a form to do a global search-and-replace, with the changes to every
 * page showing up as a wiki edit, with the administrator who performed
 * the replacement as the user, and an edit summary that looks like
 * "Text replace: 'search string' * to 'replacement string'".
 *
 * If the replacement string is blank, or is already found in the wiki,
 * the page provides a warning prompt to the user before doing the
 * replacement, since it is not easily reversible.
 *
 * @version 0.1
 * @author Yaron Koren
 */

if (!defined('MEDIAWIKI')) die();

global $IP;
require_once( "$IP/includes/SpecialPage.php" );
$grIP = $IP . '/extensions/ReplaceText';

$wgExtensionFunctions[] = 'grSetupExtension';

if (version_compare($wgVersion, '1.11', '>=')) {
	$wgExtensionMessagesFiles['ReplaceText'] = $grIP . '/ReplaceText.i18n.php';
} else {
	$wgExtensionFunctions[] = 'grfLoadMessagesManually';
}

function grSetupExtension() {
	global $wgVersion, $wgExtensionCredits;

	if (version_compare($wgVersion, '1.11', '>='))
		wfLoadExtensionMessages( 'ReplaceText' );

	// credits
	$wgExtensionCredits['specialpage'][] = array(
		'name' => 'Replace Text',
		'version' => '0.1',
		'author' => 'Yaron Koren',
		'url' => 'http://www.mediawiki.org/wiki/Extension:Text_Replace',
		'description' => 'A special page that lets administrators run a global search-and-replace',
		'descriptionmsg'  => 'replacetext-desc',
	);

	// the 'delete' specifies that only users who can delete pages
	// (usually, sysops) can access this page
	SpecialPage::addPage(new SpecialPage('ReplaceText', 'delete', true, 'doReplaceText', false));
}

/**
 * Initialize messages - these settings must be applied later on, since
 * the MessageCache does not exist yet when the settings are loaded in
 * LocalSettings.php.
 * Function based on version in ContributionScores extension
 */
function grfInitMessages() {
	global $wgVersion, $wgExtensionFunctions;
}

/**
 * Setting of message cache for versions of MediaWiki that do not support
 * wgExtensionFunctions - based on ceContributionScores() in
 * ContributionScores extension
 */
function grfLoadMessagesManually() {
	global $grIP, $wgMessageCache;

	# add messages
	require($grIP . '/ReplaceText.i18n.php');
	foreach($messages as $key => $value) {
		$wgMessageCache->addMessages($messages[$key], $key);
	}
}

function displayConfirmForm($message) {
	global $wgRequest;
	$target_str = $wgRequest->getVal('target_str');
	$replacement_str = $wgRequest->getVal('replacement_str');
	$continue_label = wfMsg('replacetext_continue');
	$cancel_label = wfMsg('replacetext_cancel');
	$replace_label = wfMsg('replacetext_replace');
	$text =<<<END
	<form method="post" action="">
	<input type="hidden" name="target_str" value="$target_str">
	<input type="hidden" name="replacement_str" value="$replacement_str">
	<input type="hidden" name="replace" value="$replace_label">
	<p>$message</p>
	<p><input type="Submit" name="confirm" value="$continue_label"></p>
	<p>$cancel_label</p>
	</form>

END;
	return $text;
}

function doReplaceText() {
  global $wgUser, $wgOut, $wgRequest;

  if ($wgRequest->getCheck('replace')) {
    $dbr =& wfGetDB( DB_SLAVE );
    $fname = 'doReplaceText';
    $target_str = $wgRequest->getVal('target_str');
    $replacement_str = $wgRequest->getVal('replacement_str');

    // create an array of all the page titles for the wiki
    $res = $dbr->select( 'page',
	array( 'page_title', 'page_namespace' ),
	array( 'page_is_redirect' => false ),
	$fname
    );

    $titles = array();
    while( $s = $dbr->fetchObject( $res ) ) {
      // ignore pages in Talk and MediaWiki namespaces
      if (($s->page_namespace != NS_TALK) && ($s->page_namespace != NS_MEDIAWIKI)) {
        $title = Title::newFromText($s->page_title, $s->page_namespace);
        $titles[] = $title;
      }
    }

    if (! $wgRequest->getCheck('confirm')) {
      // display a page to make the user confirm the replacement, if the
      // replacement string is either blank or found elsewhere on the wiki
      // (since undoing the replacement would be difficult in either case)
      if ($replacement_str == '') {
        $text = wfMsg('replacetext_blankwarning');
        $wgOut->addHTML(displayConfirmForm($text));
        return;
      } else {
        $num_files_with_replacement_str = 0;
        foreach ($titles as $title) {
          $article = new Article($title);
          $article_text = $article->fetchContent();
          if (strpos($article_text, $replacement_str)) {
            $num_files_with_replacement_str++;
          }
        }
        if ($num_files_with_replacement_str > 0) {
          $text = wfMsg('replacetext_warning', $num_files_with_replacement_str, $replacement_str);
          $wgOut->addHTML(displayConfirmForm($text));
          return;
        }
      }
    }

    $num_modified_files = 0;
    foreach ($titles as $title) {
      $article = new Article($title);
      $article_text = $article->fetchContent();
      $num_matches;
      $new_text = str_replace($target_str, $replacement_str, $article_text, $num_matches);
      // if there's at least one replacement, modify the page, using an edit
      // summary in the language of the wiki
      if ($num_matches > 0) {
        $edit_summary = wfMsgForContent('replacetext_editsummary', $target_str, $replacement_str);
        $article->doEdit($new_text, $edit_summary);
        $num_modified_files++;
      }
    }

    if ($num_modified_files == 0)
      $wgOut->addHTML(wfMsg('replacetext_noreplacement', $target_str));
    else
      $wgOut->addHTML(wfMsg('replacetext_success', $target_str, $replacement_str, $num_modified_files));
  } else {
    $replacement_label = wfMsg('replacetext_docu');
    $replacement_note = wfMsg('replacetext_note');
    $original_text_label = wfMsg('replacetext_originaltext');
    $replacement_text_label = wfMsg('replacetext_replacementtext');
    $replace_label = wfMsg('replacetext_replace');
    $text =<<<END
	<form method="post" action="">
	<p>$replacement_label</p>
	<p>$replacement_note</p>
	<br />
	<p>$original_text_label: <input type="text" length="10" name="target_str">
	&nbsp;
	$replacement_text_label: <input type="text" length="10" name="replacement_str"></p>
	<p><input type="Submit" name="replace" value="$replace_label"></p>
	</form>

END;
  }

  $wgOut->addHTML($text);
}
