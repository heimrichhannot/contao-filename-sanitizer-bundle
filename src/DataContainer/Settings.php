<?php

namespace HeimrichHannot\FilenameSanitizerBundle\DataContainer;

use Contao\Config;
use Contao\CoreBundle\Framework\FrameworkAwareInterface;
use Contao\CoreBundle\Framework\FrameworkAwareTrait;
use Contao\DataContainer;
use Contao\StringUtil;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Settings implements FrameworkAwareInterface, ContainerAwareInterface
{
    use FrameworkAwareTrait;
    use ContainerAwareTrait;

    const CAPITAL_LETTERS = 'capitalLetters';
    const SMALL_LETTERS = 'smallLetters';
    const NUMBERS = 'numbers';
    const SPECIAL_CHARS = 'specialChars';

    const DEFAULT_ALPHABETS = [
        self::CAPITAL_LETTERS,
        self::SMALL_LETTERS,
        self::NUMBERS,
    ];

    const DEFAULT_RULES = [
        self::CAPITAL_LETTERS,
        self::SMALL_LETTERS,
        self::NUMBERS,
    ];

    const DEFAULT_ALLOWED_SPECIAL_CHARS = '[=<>()#/]';

    public function getRulesAsOptions(DataContainer $dc)
    {
        $ruleOptions = [];

        $alphabets = StringUtil::deserialize(Config::get('fs_alphabets'), true);
        $types     = [
            static::CAPITAL_LETTERS,
            static::SMALL_LETTERS,
            static::NUMBERS,
            static::SPECIAL_CHARS
        ];

        foreach ($types as $type)
        {
            if (in_array($type, $alphabets))
            {
                $ruleOptions[] = $type;
            }
        }

        return $ruleOptions;
    }

    public function modifyPalette()
    {
        $dca        = &$GLOBALS['TL_DCA']['tl_code_config'];
        $alphabets  = StringUtil::deserialize(Config::get('fs_alphabets'), true);

        if (!in_array(static::SPECIAL_CHARS, $alphabets))
        {
            $dca['palettes']['default'] = str_replace('allowedSpecialChars', '', $dca['palettes']['default']);
        }
    }
}