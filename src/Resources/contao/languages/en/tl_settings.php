<?php

$lang = &$GLOBALS['TL_LANG']['tl_settings'];

/**
 * Fields
 */
$lang['fs_validAlphabets'][0]                     = 'Valid alphabets';
$lang['fs_validAlphabets'][1]                     = 'Choose the alphabets here that are valid for filenames.';
$lang['fs_validSpecialChars'][0]                  = 'Valid special characters (directly in a row, NOT separated through a comma or a space!)';
$lang['fs_validSpecialChars'][1]                  = 'Please type in a list of characters. IMPORtANT: Don\'t separate the characters from each other!';
$lang['fs_replaceChar'][0]                        = 'Replacement character';
$lang['fs_replaceChar'][1]                        = 'Type in the character here invalid characters should be replaced with. If you wish to remove invalid characters, leave the field empty.';
$lang['fs_condenseSeparators'][0]                 = 'Merge separators (hyphen, underscore, ...)';
$lang['fs_condenseSeparators'][1]                 = 'Choose this option in order to merge multiple separators in a row ("_____" becomes "_" e.g.).';
$lang['fs_trim'][0]                               = 'Remove leading and trailing characters';
$lang['fs_trim'][1]                               = 'Choose this option in order to remove certain leading and trailing characters (e.g. spaces).';
$lang['fs_trimChars'][0]                          = 'Leading and trailing characters to be removed (spaces are always removed; directly in a row, NOT separated through a comma or a space!)';
$lang['fs_trimChars'][1]                          = 'Please type in a list of characters. IMPORtANT: Don\'t separate the characters from each other!';
$lang['fs_charReplacements'][0]                   = 'Character replacements';
$lang['fs_charReplacements'][1]                   = 'Add character replacements here. Useful for replacing German umlauts  for example.';
$lang['fs_charReplacements_fields']['source']     = ['Source character', ''];
$lang['fs_charReplacements_fields']['target']     = ['Target character', ''];
$lang['fs_charReplacements_fields']['ignoreCase'] = ['Ignore case', ''];

/**
 * Legends
 */
$lang['filename_sanitizer_legend'] = 'Filenames';

/**
 * Reference
 */
$settingsService = System::getContainer()->get(\HeimrichHannot\FilenameSanitizerBundle\DataContainer\SettingsContainer::class);

$lang['reference']['filenameSanitizerBundle'] = [
    'validAlphabets' => [
        $settingsService::CAPITAL_LETTERS => 'Capital letters',
        $settingsService::SMALL_LETTERS   => 'Small letters',
        $settingsService::NUMBERS         => 'Numbers',
        $settingsService::SPECIAL_CHARS   => 'Special characters',
    ]
];
