<?php

$dca = &$GLOBALS['TL_DCA']['tl_settings'];
$settingsService = System::getContainer()->get('huh.filename_sanitizer.data_container.settings');

/**
 * Callbacks
 */
$dca['config']['onload_callback']['filenameSanitizer'] = ['huh.filename_sanitizer.data_container.settings', 'modifyDca'];

/**
 * Palettes
 */
$dca['palettes']['__selector__'][] = 'fs_trim';

$dca['palettes']['default'] = str_replace('{uploads_legend', '{filename_sanitizer_legend},fs_validAlphabets,fs_validSpecialChars,fs_replaceChar,fs_condenseSeparators,fs_trim;{uploads_legend', $dca['palettes']['default']);

/**
 * Subpalettes
 */
$dca['subpalettes']['fs_trim'] = 'fs_trimChars';

/**
 * Fields
 */
$fields = [
    'fs_validAlphabets'      => [
        'label'     => &$GLOBALS['TL_LANG']['tl_settings']['fs_validAlphabets'],
        'exclude'   => true,
        'options'   => [
            $settingsService::CAPITAL_LETTERS,
            $settingsService::SMALL_LETTERS,
            $settingsService::NUMBERS,
            $settingsService::SPECIAL_CHARS
        ],
        'reference' => &$GLOBALS['TL_LANG']['tl_settings']['reference']['filenameSanitizerBundle']['validAlphabets'],
        'inputType' => 'checkbox',
        'eval'      => ['tl_class' => 'w50 autoheight', 'multiple' => true, 'submitOnChange' => true],
    ],
    'fs_validSpecialChars' => [
        'label'     => &$GLOBALS['TL_LANG']['tl_settings']['fs_validSpecialChars'],
        'exclude'   => true,
        'inputType' => 'text',
        'eval'      => ['tl_class' => 'w50'],
    ],
    'fs_replaceChar' => [
        'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['fs_replaceChar'],
        'exclude'                 => true,
        'inputType'               => 'text',
        'eval'                    => ['maxlength' => 1, 'tl_class' => 'w50 clr'],
    ],
    'fs_condenseSeparators' => [
        'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['fs_condenseSeparators'],
        'exclude'                 => true,
        'inputType'               => 'checkbox',
        'eval'                    => ['tl_class' => 'w50'],
    ],
    'fs_trim' => [
        'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['fs_trim'],
        'exclude'                 => true,
        'inputType'               => 'checkbox',
        'eval'                    => ['tl_class' => 'w50 clr', 'submitOnChange' => true],
    ],
    'fs_trimChars' => [
        'label'     => &$GLOBALS['TL_LANG']['tl_settings']['fs_trimChars'],
        'exclude'   => true,
        'inputType' => 'text',
        'eval'      => ['tl_class' => 'w50'],
    ],
];

$dca['fields'] += $fields;