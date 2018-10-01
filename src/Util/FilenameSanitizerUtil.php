<?php

/*
 * Copyright (c) 2018 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\FilenameSanitizerBundle\EventListener;

use Contao\Config;
use Contao\CoreBundle\Framework\FrameworkAwareInterface;
use Contao\CoreBundle\Framework\FrameworkAwareTrait;
use Contao\StringUtil;
use Contao\System;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class FilenameSanitizerUtil implements FrameworkAwareInterface, ContainerAwareInterface
{
    use FrameworkAwareTrait;
    use ContainerAwareTrait;

    public function sanitizeString(string $string)
    {
        $regExp = '';
        $alphabets = StringUtil::deserialize(Config::get('fs_validAlphabets'), true);
        $settingsService = System::getContainer()->get('huh.filename_sanitizer.data_container.settings');

        if (\in_array($settingsService::SMALL_LETTERS, $alphabets)) {
            $regExp .= 'az';
        }

        if (\in_array($settingsService::CAPITAL_LETTERS, $alphabets)) {
            $regExp .= 'AZ';
        }

        if (\in_array($settingsService::NUMBERS, $alphabets)) {
            $regExp .= '09';
        }

        if (\in_array($settingsService::SPECIAL_CHARS, $alphabets)) {
            $regExp .= Config::get('fs_validSpecialChars');
        }

        $string = preg_replace('/[^'.$regExp.']/i', Config::get('fs_replaceChar'), $string);

        if (Config::get('fs_condenseSeparators')) {
            while (false !== strpos($string, $settingsService::DOUBLE_SEPERATORS)) {
                $string = str_replace($settingsService::DOUBLE_SEPERATORS, $settingsService::SEPERATORS, $string);
            }
        }

        if (Config::get('fs_trim')) {
            $string = trim($string, Config::get('fs_trimChars'));
            $string = trim($string);
        }

        return $string;
    }
}
