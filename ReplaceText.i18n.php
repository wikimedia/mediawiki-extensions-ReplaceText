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
	'replacetext-desc' => 'Provides a [[Special:ReplaceText|special page]] to allow administrators to do a global string find-and-replace on all the content pages of a wiki',
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

/** Arabic (العربية)
 * @author Alnokta
 */
$messages['ar'] = array(
	'replacetext'                 => 'استبدل النص',
	'replacetext_originaltext'    => 'النص الأصلي',
	'replacetext_replacementtext' => 'نص الاستبدال',
	'replacetext_replace'         => 'استبدل',
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

/** Javanese (Basa Jawa)
 * @author Meursault2004
 */
$messages['jv'] = array(
	'replacetext'              => 'Ganti tèks',
	'replacetext_originaltext' => 'Tèks asli',
);

/** Malayalam (മലയാളം)
 * @author Shijualex
 */
$messages['ml'] = array(
	'replacetext_continue' => 'തുടരുക',
);

/** Dutch (Nederlands)
 * @author Siebrand
 * @author SPQRobin
 */
$messages['nl'] = array(
	'replacetext'                 => 'Tekst vervangen',
	'replacetext-desc'            => "Beheerders kunnen via een [[Special:ReplaceText|speciale pagina]] tekst zoeken en vervangen in alle pagina's",
	'replacetext_docu'            => "Om een stuk tekst te vervangen door een ander stuk tekst in alle pagina's van de wiki, kunt u hier deze twee tekstdelen ingeven en daarna op 'Vervangen' klikken. Uw naam wordt opgenomen in de geschiedenis van de pagina als verantwoordelijke voor de wijzigingen.",
	'replacetext_note'            => "Nota bene: de tekst wordt niet vevangen in overlegpagina's en projectpagina's. Paginanamen worden ook niet aangepast.",
	'replacetext_originaltext'    => 'Oorspronkelijke tekst',
	'replacetext_replacementtext' => 'Vervangende tekst',
	'replacetext_replace'         => 'Vervangen',
	'replacetext_success'         => "'$1' is vervangen door '$2' in $3 pagina's.",
	'replacetext_noreplacement'   => "Er is niets vangen. Er waren geen pagina's die de tekst '$1' bevatten.",
	'replacetext_warning'         => "Er zijn $1 pagina's die het te vervangen tesktdeel al '$2' al bevatten. Als u nu doorgaat met vervangen, kunt u geen onderscheid meer maken. Wilt u doorgaan?",
	'replacetext_blankwarning'    => 'Omdat u tekst vervangt door niets, kan deze handeling niet ongedaan gemaakt worden. Wilt u doorgaan?',
	'replacetext_continue'        => 'Doorgaan',
	'replacetext_cancel'          => '(Klik op de knop "Terug" om deze handeling te annuleren)',
	'replacetext_editsummary'     => "Tekst vervangen - '$1' door '$2'",
);

/** Norwegian (bokmål)‬ (‪Norsk (bokmål)‬)
 * @author Jon Harald Søby
 */
$messages['no'] = array(
	'replacetext'                 => 'Erstatt tekst',
	'replacetext-desc'            => 'Lar administratorer kunne [[Special:ReplaceText|erstatte tekst]] på alle innholdssider på en wiki.',
	'replacetext_docu'            => 'For å erstatte én tekststreng med en annen på alle datasider på denne wikien kan du skrive inn de to tekstene her og trykke «Erstatt». Navnet ditt vil stå i sidehistorikkene som den som er ansvarlig for endringene.',
	'replacetext_note'            => 'Merk: dette vil ikke erstatte tekst på diskusjonssider og prosjektsider, og vil ikke erstatte tekst i sidetitler.',
	'replacetext_originaltext'    => 'Originaltekst',
	'replacetext_replacementtext' => 'Erstatningstekst',
	'replacetext_replace'         => 'Erstatt',
	'replacetext_success'         => 'Erstattet «$1» med «$2» på $3 sider.',
	'replacetext_noreplacement'   => 'Ingen erstatninger ble gjort; ingen sider ble funnet med strengen «$1».',
	'replacetext_warning'         => 'Det er $1 sider som allerede har erstatningsteksten «$2». Om du gjør denne erstatningen vil du ikke kunne skille ut dine erstatninger fra denne teksten. Fortsette med erstattingen?',
	'replacetext_blankwarning'    => 'Fordi erstatningsteksten er tom vil denne handlingen ikke kunne angres automatisk; fortsette?',
	'replacetext_continue'        => 'Fortsett',
	'replacetext_cancel'          => '(Trykk på «Tilbake»-knappen for å avbryte handlingen.)',
	'replacetext_editsummary'     => 'Teksterstatting – «$1» til «$2»',
);

/** Occitan (Occitan)
 * @author Cedric31
 */
$messages['oc'] = array(
	'replacetext'                 => 'Remplaçar lo tèxt',
	'replacetext-desc'            => 'Provesís una [[Special:ReplaceText|pagina especiala]] que permet als administrators de remplaçar de cadenas de caractèrs per d’autras sus l’ensemble del wiki',
	'replacetext_docu'            => "Per remplaçar una cadena de caractèrs amb una autra sus l'ensemble de las donadas de las paginas d'aqueste wiki, podètz picar los dos tèxtes aicí e clicar sus « Remplaçar ». Vòstre nom apareiserà dins l'istoric de las paginas tal coma un utilizaire autor dels cambiaments.",
	'replacetext_note'            => 'Nòta : aquò remplaçarà pas lo tèxt dins las paginas de discussion ni mai dins las paginas « projècte ». Remplaçarà pas, tanpauc, lo tèxt dins lo títol ele meteis.',
	'replacetext_originaltext'    => 'Tèxt original',
	'replacetext_replacementtext' => 'Tèxt novèl',
	'replacetext_replace'         => 'Remplaçar',
	'replacetext_success'         => 'A remplaçat « $1 » per « $2 » dins « $3 » fichièrs.',
	'replacetext_noreplacement'   => 'Cap de remplaçamet es pas estat efectuat ; cap de fichièr que conten la cadena « $1 » es pas estat trobat.',
	'replacetext_warning'         => "I a $1 fichièrs que conten(on) la cadena de remplaçament « $2 » ; se efectuatz aquesta substitucion, poiretz pas separar vòstres cambiaments a partir d'aquestas cadenas. Volètz contunhar aquestas substitucions ?",
	'replacetext_blankwarning'    => 'Perque la cadena de remplaçament es voida, aquesta operacion serà irreversibla ; volètz contunhar ?',
	'replacetext_continue'        => 'Contunhar',
	'replacetext_cancel'          => "(clicatz sul boton  « Retorn » per anullar l'operacion.)",
	'replacetext_editsummary'     => 'Remplaçament del tèxt — « $1 » per « $2 »',
);

/** Portuguese (Português)
 * @author 555
 */
$messages['pt'] = array(
	'replacetext'                 => 'Substituir texto',
	'replacetext-desc'            => 'Provê uma [[Special:ReplaceText|página especial]] que permite que administradores procurem e substituam uma "string" global em todas as páginas de conteúdo de uma wiki.',
	'replacetext_docu'            => 'Para substituir uma "string" de texto por outra em todas as páginas desta wiki, você precisa fornecer as duas peças de texto a seguir, pressionando o botão \'Substituir\'. Seu nome de utilizador aparecerá nos históricos de páginas como o responsável por ter feito as alterações.',
	'replacetext_note'            => 'Nota: isto não substituirá textos em páginas de discussão e organizacionais do projeto, além de não substituir texto nos títulos de páginas.',
	'replacetext_originaltext'    => 'Texto original',
	'replacetext_replacementtext' => 'Novo texto',
	'replacetext_replace'         => 'Substituir',
	'replacetext_success'         => "'$1' foi substituído por '$2' em $3 páginas.",
	'replacetext_noreplacement'   => 'Não foram feitas substituições de texto; não foram encontradas páginas contendo a "string" \'$1\'.',
	'replacetext_warning'         => 'Há $1 páginas que atualmente já possuem a "string" de substituição (\'$2\'); se você prosseguir, não será possível distinguir as substituições feitas por você desse texto já existente. Deseja prosseguir?',
	'replacetext_blankwarning'    => 'Uma vez que a "string" de novo texto foi deixada em branco, esta operação não será reversível. Prosseguir?',
	'replacetext_continue'        => 'Prosseguir',
	'replacetext_cancel'          => '(pressione o botão "voltar" de seu navegador para cancelar a operação.)',
	'replacetext_editsummary'     => "Substituindo texto '$1' por '$2'",
);

/** Slovak (Slovenčina)
 * @author Helix84
 */
$messages['sk'] = array(
	'replacetext'                 => 'Nahradiť text',
	'replacetext-desc'            => 'Poskytuje [[Special:ReplaceText|špeciálnu stránku]], ktorá správcom umožňuje globálne nájsť a nahradiť text na všetkých stránkach celej wiki.',
	'replacetext_docu'            => 'Nájsť text na všetkých stránkach tejto wiki a nahradiť ho iným textom môžete tak, že sem napíšete texty a stlačíte „Nahradiť”. V histórii úprav sa zaznamená vaše meno.',
	'replacetext_note'            => 'Pozn.: Týmto nemožno nahradiť text na diskusných a projektových stránkach ani text v samotných názvoch stránok.',
	'replacetext_originaltext'    => 'Pôvodný text',
	'replacetext_replacementtext' => 'Nahradiť textom',
	'replacetext_replace'         => 'Nahradiť',
	'replacetext_success'         => 'Text „$1” bol nahradený textom „$2” na {{PLURAL:$3|$3 stránke|$3 stránkach}}.',
	'replacetext_noreplacement'   => 'Nevykonalo sa žadne nahradenie textu; nenašli sa žiadne stránky obsahujúce text „$1”.',
	'replacetext_warning'         => '$1 stránok už obsahuje text „$2”, ktorým chcete text nahradiť; ak budete pokračovať a text nahradíte, nebudete môcť odlíšiť vaše nahradenia od existujúceho textu, ktorý tento reťazec už obsahuje. Pokračovať?',
	'replacetext_blankwarning'    => 'Pretože text, ktorým text chcete nahradiť je prázdny, operácia bude nevratná. Pokračovať?',
	'replacetext_continue'        => 'Pokračovať',
	'replacetext_cancel'          => '(Operáciu zrušíte stlačením tlačidla „Späť” vo vašom prehliadači.)',
	'replacetext_editsummary'     => 'Nahradenie textu „$1” textom „$2”',
);

/** Serbian Cyrillic ekavian (ћирилица)
 * @author Sasa Stefanovic
 */
$messages['sr-ec'] = array(
	'replacetext_originaltext'    => 'Оригинални текст',
	'replacetext_replacementtext' => 'Текст за преснимавање',
	'replacetext_replace'         => 'Пресними',
	'replacetext_success'         => "Преснимљен '$1' са '$2' на $3 страница.",
	'replacetext_continue'        => 'Настави',
);

/** Swedish (Svenska)
 * @author M.M.S.
 */
$messages['sv'] = array(
	'replacetext'                 => 'Ersätt text',
	'replacetext-desc'            => 'Låter administratörer [[Special:ReplaceText|ersätta text]] på alla innehållssidor på en wiki',
	'replacetext_docu'            => 'För att ersätta en textträng med en annan på alla datasidor på den här wikin kan du skriva in de två texterna här och klicka på "Ersätt". Ditt namn kommer visas i sidhistoriken som den som är ansvarig för ändringarna.',
	'replacetext_note'            => 'Notera: detta kommer inte ersätta text på diskussionssidor och projektsidor, och kommer inte ersätts text i sidtitlar.',
	'replacetext_originaltext'    => 'Originaltext',
	'replacetext_replacementtext' => 'Ersättningstext',
	'replacetext_replace'         => 'Ersätt',
	'replacetext_success'         => 'Ersatte "$1" med "$2" på $3 sidor.',
	'replacetext_noreplacement'   => 'Inga ersättningar gjordes; inga sidor hittades med strängen "$1".',
	'replacetext_warning'         => 'Det finns $1 sidor som redan har ersättningssträngen "$2". Om du gör den här ersättningen kommer du inte kunna separera dina ersättningar från den här texten. Vill du fortsätta med ersättningen?',
	'replacetext_blankwarning'    => 'Eftersom ersättningstexten är tom kommer den här handlingen inte kunna upphävas; vill du fortsätta?',
	'replacetext_continue'        => 'Fortsätt',
	'replacetext_cancel'          => '(Klicka på "Tillbaka"-knappen för att avbryta handlingen.)',
	'replacetext_editsummary'     => 'Textersättning - "$1" till "$2"',
);

/** Thai (ไทย)
 * @author Passawuth
 */
$messages['th'] = array(
	'replacetext_originaltext' => 'ข้อความดั้งเดิม',
);

