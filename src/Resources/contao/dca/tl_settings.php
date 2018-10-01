<?php

$dca = &$GLOBALS['TL_DCA']['tl_settings'];

/**
 * Palettes
 */
$dca['palettes']['default'] = str_replace('gdMaxImgHeight', 'gdMaxImgHeight,fs_validAlphabets,fs_rules', $dca['palettes']['default']);

/**
 * Fields
 */
$fields = [
    'fs_validAlphabets' => [
        'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['fs_validAlphabets'],
        'exclude'                 => true,
        'options' => [
            System::getContainer()->get('huh.filename_sanitizer.data_container.settings')::CAPITAL_LETTERS,
            System::getContainer()->get('huh.filename_sanitizer.data_container.settings')::SMALL_LETTERS,
            System::getContainer()->get('huh.filename_sanitizer.data_container.settings')::NUMBERS,
            System::getContainer()->get('huh.filename_sanitizer.data_container.settings')::SPECIAL_CHARS
        ],
        'inputType'               => 'checkbox',
        'eval'                    => ['tl_class' => 'w50', 'multiple' => true, 'submitOnChange' => true],
        'sql'                     => "char(1) NOT NULL default ''"
    ],
    'fs_rules'                => [
        'label'            => &$GLOBALS['TL_LANG']['tl_settings']['fs_rules'],
        'exclude'          => true,
        'inputType'        => 'checkbox',
        'options_callback' => ['huh.code_generator.backend.code_config', 'getRulesAsOptions'],
        'reference'        => &$GLOBALS['TL_LANG']['tl_code_config']['reference']['rules'],
        'eval'             => ['multiple' => true, 'tl_class' => 'w50'],
        'sql'              => "blob NULL"
    ],
    'fs_allowedSpecialChars'  => [
        'label'     => &$GLOBALS['TL_LANG']['tl_settings']['fs_allowedSpecialChars'],
        'exclude'   => true,
        'inputType' => 'text',
        'default'   => '[=<>()#/]',
        'eval'      => ['tl_class' => 'w50 clr'],
        'sql'       => "varchar(255) NOT NULL default ''"
    ]
];

$dca['fields'] += $fields;