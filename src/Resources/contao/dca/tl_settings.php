<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use HeimrichHannot\FilenameSanitizerBundle\DataContainer\SettingsContainer;

$dca = &$GLOBALS['TL_DCA']['tl_settings'];

/**
 * Callbacks
 */
$dca['config']['onload_callback']['filenameSanitizer'] = [SettingsContainer::class, 'modifyDca'];

/**
 * Palettes
 */

PaletteManipulator::create()
    ->addLegend('filename_sanitizer_legend', 'uploads_legend', PaletteManipulator::POSITION_AFTER)
    ->addField('fs_validAlphabets', 'filename_sanitizer_legend', PaletteManipulator::POSITION_APPEND)
    ->addField('fs_validSpecialChars', 'filename_sanitizer_legend', PaletteManipulator::POSITION_APPEND)
    ->addField('fs_replaceChar', 'filename_sanitizer_legend', PaletteManipulator::POSITION_APPEND)
    ->addField('fs_condenseSeparators', 'filename_sanitizer_legend', PaletteManipulator::POSITION_APPEND)
    ->addField('fs_trim', 'filename_sanitizer_legend', PaletteManipulator::POSITION_APPEND)
    ->addField('fs_charReplacements', 'filename_sanitizer_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_settings');

$dca['palettes']['__selector__'][] = 'fs_trim';
$dca['subpalettes']['fs_trim'] = 'fs_trimChars';

/**
 * Fields
 */
$fields = [
    'fs_validAlphabets' => [
        'label' => &$GLOBALS['TL_LANG']['tl_settings']['fs_validAlphabets'],
        'exclude' => true,
        'options' => [
            SettingsContainer::CAPITAL_LETTERS,
            SettingsContainer::SMALL_LETTERS,
            SettingsContainer::NUMBERS,
            SettingsContainer::SPECIAL_CHARS
        ],
        'reference' => &$GLOBALS['TL_LANG']['tl_settings']['reference']['filenameSanitizerBundle']['validAlphabets'],
        'inputType' => 'checkbox',
        'eval' => ['tl_class' => 'w50 autoheight', 'multiple' => true, 'submitOnChange' => true],
    ],
    'fs_validSpecialChars' => [
        'label' => &$GLOBALS['TL_LANG']['tl_settings']['fs_validSpecialChars'],
        'exclude' => true,
        'inputType' => 'text',
        'eval' => ['tl_class' => 'w50'],
    ],
    'fs_replaceChar' => [
        'label' => &$GLOBALS['TL_LANG']['tl_settings']['fs_replaceChar'],
        'exclude' => true,
        'inputType' => 'text',
        'eval' => ['maxlength' => 1, 'tl_class' => 'w50 clr'],
    ],
    'fs_condenseSeparators' => [
        'label' => &$GLOBALS['TL_LANG']['tl_settings']['fs_condenseSeparators'],
        'exclude' => true,
        'inputType' => 'checkbox',
        'eval' => ['tl_class' => 'w50'],
    ],
    'fs_trim' => [
        'label' => &$GLOBALS['TL_LANG']['tl_settings']['fs_trim'],
        'exclude' => true,
        'inputType' => 'checkbox',
        'eval' => ['tl_class' => 'w50 clr', 'submitOnChange' => true],
    ],
    'fs_trimChars' => [
        'label' => &$GLOBALS['TL_LANG']['tl_settings']['fs_trimChars'],
        'exclude' => true,
        'inputType' => 'text',
        'eval' => ['tl_class' => 'w50'],
    ],
    'fs_charReplacements' => [
        'label' => &$GLOBALS['TL_LANG']['tl_settings']['fs_charReplacements'],
        'inputType' => 'multiColumnEditor',
        'eval' => [
            'tl_class' => 'long clr',
            'multiColumnEditor' => [
                'minRowCount' => 0,
                'sortable' => true,
                'fields' => [
                    'source' => [
                        'label' => &$GLOBALS['TL_LANG']['tl_settings']['fs_charReplacements_fields']['source'],
                        'inputType' => 'text',
                        'eval' => ['maxlength' => 255, 'mandatory' => true],
                    ],
                    'target' => [
                        'label' => &$GLOBALS['TL_LANG']['tl_settings']['fs_charReplacements_fields']['target'],
                        'inputType' => 'text',
                        'eval' => ['maxlength' => 255, 'mandatory' => true],
                    ],
                    'ignoreCase' => [
                        'label' => &$GLOBALS['TL_LANG']['tl_settings']['fs_charReplacements_fields']['ignoreCase'],
                        'inputType' => 'checkbox'
                    ],
                ],
            ],
        ]
    ],
];

$dca['fields'] = array_merge($dca['fields'], $fields);