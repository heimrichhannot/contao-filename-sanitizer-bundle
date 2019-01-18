<?php

/*
 * Copyright (c) 2019 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\FilenameSanitizerBundle\Util;

use Contao\Config;
use Contao\CoreBundle\Framework\FrameworkAwareInterface;
use Contao\CoreBundle\Framework\FrameworkAwareTrait;
use Contao\File;
use Contao\Folder;
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

        if (\in_array($settingsService::SMALL_LETTERS, $alphabets) && \in_array($settingsService::CAPITAL_LETTERS, $alphabets)) {
            $regExp .= 'a-zA-Z';
        }

        if (!\in_array($settingsService::SMALL_LETTERS, $alphabets) && \in_array($settingsService::CAPITAL_LETTERS, $alphabets)) {
            $string = strtoupper($string);
            $regExp .= 'A-Z';
        }

        if (\in_array($settingsService::SMALL_LETTERS, $alphabets) && !\in_array($settingsService::CAPITAL_LETTERS, $alphabets)) {
            $string = strtolower($string);
            $regExp .= 'a-z';
        }

        if (\in_array($settingsService::NUMBERS, $alphabets)) {
            $regExp .= '0-9';
        }

        if (\in_array($settingsService::SPECIAL_CHARS, $alphabets)) {
            $regExp .= preg_quote(Config::get('fs_validSpecialChars'));
        }

        if (!$regExp) {
            return $string;
        }

        $string = preg_replace('/[^'.$regExp.']/', Config::get('fs_replaceChar'), $string);

        if (Config::get('fs_condenseSeparators')) {
            foreach ($settingsService::DOUBLE_SEPERATORS as $doubleSeperator) {
                while (false !== strpos($string, $doubleSeperator)) {
                    $string = str_replace($doubleSeperator, substr($doubleSeperator, 0, 1), $string);
                }
            }
        }

        if (Config::get('fs_trim')) {
            // in any case trim spaces and the replace character
            $string = trim($string);
            $string = trim($string, Config::get('fs_replaceChar'));

            $string = trim($string, Config::get('fs_trimChars'));
        }

        return $string;
    }

    public function sanitizeFile(File $file)
    {
        if (!$file->exists()) {
            return;
        }

        $filename = str_replace('.'.$file->extension, '', $file->name);
        $folder = str_replace($this->container->get('huh.utils.container')->getProjectDir().'/', '', $file->dirname);
        $path = $folder.'/'.$this->sanitizeString($filename).'.'.$file->extension;
        $file->renameTo($path);
    }

    public function sanitizeFolder(Folder $folder)
    {
        $parentFolder = str_replace($this->container->get('huh.utils.container')->getProjectDir().'/', '', $folder->dirname);
        $path = $parentFolder.'/'.$this->sanitizeString($folder->basename);

        $folder->renameTo($path);
    }
}
