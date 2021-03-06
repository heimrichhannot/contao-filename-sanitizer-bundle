<?php

/*
 * Copyright (c) 2021 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\FilenameSanitizerBundle\DataContainer;

use Contao\Config;
use Contao\StringUtil;

class SettingsContainer
{
    const CAPITAL_LETTERS = 'capitalLetters';
    const SMALL_LETTERS = 'smallLetters';
    const NUMBERS = 'numbers';
    const SPECIAL_CHARS = 'specialChars';

    const DEFAULTS = [
        'fs_replaceChar' => '-',
        'fs_validAlphabets' => [
            self::SMALL_LETTERS,
            self::NUMBERS,
        ],
        'fs_validSpecialChars' => '-',
        'fs_trim' => true,
        'fs_trimChars' => '-_.,;|',
        'fs_condenseSeparators' => true,
        'fs_charReplacements' => [
            [
                'source' => 'ä',
                'target' => 'ae',
                'ignoreCase' => true,
            ],
            [
                'source' => 'ö',
                'target' => 'oe',
                'ignoreCase' => true,
            ],
            [
                'source' => 'ü',
                'target' => 'ue',
                'ignoreCase' => true,
            ],
            [
                'source' => 'ß',
                'target' => 'ss',
                'ignoreCase' => true,
            ],
        ],
    ];

    const DOUBLE_SEPARATORS = ['--', '__', '––'];

    public function modifyDca()
    {
        $dca = &$GLOBALS['TL_DCA']['tl_settings'];
        $alphabets = StringUtil::deserialize(Config::get('fs_validAlphabets'), true);

        if (!\in_array(static::SPECIAL_CHARS, $alphabets)) {
            $dca['palettes']['default'] = str_replace('fs_validSpecialChars', '', $dca['palettes']['default']);
        }
    }

    public function setDefaults()
    {
        foreach (static::DEFAULTS as $field => $value) {
            if (null === Config::get($field)) {
                Config::persist($field, \is_array($value) ? serialize($value) : $value);
                Config::set($field, \is_array($value) ? serialize($value) : $value);
            }
        }
    }
}
