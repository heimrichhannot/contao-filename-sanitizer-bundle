<?php

$lang = &$GLOBALS['TL_LANG']['tl_settings'];

/**
 * Fields
 */
$lang['fs_validAlphabets'][0]     = 'Zulässige Alphabete';
$lang['fs_validAlphabets'][1]     = 'Bitte wählen Sie die Alphabete aus, die für Dateinamen erlaubt sein sollen.';
$lang['fs_validSpecialChars'][0]  = 'Zulässige Sonderzeichen (direkt hintereinander, nicht durch Komma oder Leerzeichen trennen!)';
$lang['fs_validSpecialChars'][1]  = 'Bitte geben Sie eine Liste von Zeichen ein. WICHTIG: Trennen Sie die Zeichen voneinander!';
$lang['fs_replaceChar'][0]        = 'Ersetzungszeichen';
$lang['fs_replaceChar'][1]        = 'Geben Sie hier das Zeichen ein, mit dem ungültige Zeichen ersetzt werden sollen. Lassen Sie das Feld leer, wenn ungültige Zeichen gelöscht werden sollen.';
$lang['fs_condenseSeparators'][0] = 'Trennzeichen zusammenfassen (Bindestrich, Unterstrich, ...)';
$lang['fs_condenseSeparators'][1] = 'Wählen Sie diese Option, wenn aufeinanderfolgende Trennzeichen zusammenefasst werden sollen (aus "_____" wird bspw. "_").';
$lang['fs_trim'][0]               = 'Führende und schließende Zeichen entfernen';
$lang['fs_trim'][1]               = 'Wählen Sie diese Option, wenn führende und schließende Zeichen entfernt werden sollen (bspw. Leerzeichen).';
$lang['fs_trimChars'][0]          = 'Zu entfernende führende und schließende Zeichen (Leerzeichen werden immer entfernt; direkt hintereinander, nicht durch Kommata oder Leerzeichen trennen!)';
$lang['fs_trimChars'][1]          = 'Bitte geben Sie eine Liste von Zeichen ein. WICHTIG: Trennen Sie die Zeichen voneinander!';

/**
 * Legends
 */
$lang['filename_sanitizer_legend'] = 'Dateinamen';

/**
 * Reference
 */
$settingsService = System::getContainer()->get('huh.filename_sanitizer.data_container.settings');

$lang['reference']['filenameSanitizerBundle'] = [
    'validAlphabets' => [
        $settingsService::CAPITAL_LETTERS => 'Großbuchstaben',
        $settingsService::SMALL_LETTERS   => 'Kleinbuchstaben',
        $settingsService::NUMBERS         => 'Zahlen',
        $settingsService::SPECIAL_CHARS   => 'Sonderzeichen',
    ]
];