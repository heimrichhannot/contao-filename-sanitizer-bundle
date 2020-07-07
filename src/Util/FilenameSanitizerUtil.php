<?php

/*
 * Copyright (c) 2020 Heimrich & Hannot GmbH
 *
 * @license LGPL-3.0-or-later
 */

namespace HeimrichHannot\FilenameSanitizerBundle\Util;

use Contao\Config;
use Contao\Controller;
use Contao\File;
use Contao\Folder;
use Contao\Message;
use Contao\StringUtil;
use HeimrichHannot\FilenameSanitizerBundle\DataContainer\SettingsContainer;
use HeimrichHannot\FilenameSanitizerBundle\Event\AfterFilenameSanitizationEvent;
use HeimrichHannot\FilenameSanitizerBundle\Event\AfterFolderSanitizationEvent;
use HeimrichHannot\FilenameSanitizerBundle\Event\AfterStringSanitizationEvent;
use HeimrichHannot\FilenameSanitizerBundle\Event\BeforeFilenameSanitizationEvent;
use HeimrichHannot\FilenameSanitizerBundle\Event\BeforeFolderSanitizationEvent;
use HeimrichHannot\FilenameSanitizerBundle\Event\BeforeStringSanitizationEvent;
use HeimrichHannot\UtilsBundle\Container\ContainerUtil;
use HeimrichHannot\UtilsBundle\Url\UrlUtil;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class FilenameSanitizerUtil
{
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;
    /**
     * @var SettingsContainer
     */
    private $settingsContainer;
    /**
     * @var ContainerUtil
     */
    private $containerUtil;
    /**
     * @var UrlUtil
     */
    private $urlUtil;

    public function __construct(EventDispatcherInterface $eventDispatcher, SettingsContainer $settingsContainer, ContainerUtil $containerUtil, UrlUtil $urlUtil)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->settingsContainer = $settingsContainer;
        $this->containerUtil = $containerUtil;
        $this->urlUtil = $urlUtil;
    }

    public function sanitizeString(string $string)
    {
        $event = $this->eventDispatcher->dispatch(BeforeStringSanitizationEvent::NAME, new BeforeStringSanitizationEvent($string));
        $string = $event->getString();

        $regExp = '';
        $alphabets = StringUtil::deserialize(Config::get('fs_validAlphabets'), true);

        if (Config::get('fs_charReplacements')) {
            foreach (StringUtil::deserialize(Config::get('fs_charReplacements'), true) as $replacement) {
                $regExpDelimiter = false === strpos($replacement['source'], '@') ? '@' : '/';
                $pattern = $regExpDelimiter.$replacement['source'].$regExpDelimiter.($replacement['ignoreCase'] ? 'iu' : 'u');

                $string = preg_replace($pattern, $replacement['target'], $string);
            }
        }

        if (\in_array($this->settingsContainer::SMALL_LETTERS, $alphabets) && \in_array($this->settingsContainer::CAPITAL_LETTERS, $alphabets)) {
            $regExp .= 'a-zA-Z';
        }

        if (!\in_array($this->settingsContainer::SMALL_LETTERS, $alphabets) && \in_array($this->settingsContainer::CAPITAL_LETTERS, $alphabets)) {
            $string = strtoupper($string);
            $regExp .= 'A-Z';
        }

        if (\in_array($this->settingsContainer::SMALL_LETTERS, $alphabets) && !\in_array($this->settingsContainer::CAPITAL_LETTERS, $alphabets)) {
            $string = strtolower($string);
            $regExp .= 'a-z';
        }

        if (\in_array($this->settingsContainer::NUMBERS, $alphabets)) {
            $regExp .= '0-9';
        }

        if (\in_array($this->settingsContainer::SPECIAL_CHARS, $alphabets)) {
            $regExp .= preg_quote(Config::get('fs_validSpecialChars'));
        }

        if (!$regExp) {
            return $string;
        }

        $string = preg_replace('/[^'.$regExp.']/', Config::get('fs_replaceChar'), $string);

        if (Config::get('fs_condenseSeparators')) {
            foreach ($this->settingsContainer::DOUBLE_SEPARATORS as $doubleSeperator) {
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

        $event = $this->eventDispatcher->dispatch(AfterStringSanitizationEvent::NAME, new AfterStringSanitizationEvent($string));
        $string = $event->getString();

        return $string;
    }

    public function sanitizeFile(File $file)
    {
        if (!$file->exists()) {
            return;
        }

        $projectDir = $this->containerUtil->getProjectDir();

        $event = $this->eventDispatcher->dispatch(BeforeFilenameSanitizationEvent::NAME, new BeforeFilenameSanitizationEvent($file));

        /** @var File $file */
        $file = $event->getFile();

        $pathInfo = pathinfo($file->path);

        $filename = $pathInfo['filename'];
        $folder = str_replace($projectDir.'/', '', $file->dirname);
        $path = $folder.'/'.$this->sanitizeString($filename).'.'.$file->extension;

        // if the file's name already had been sane, we're finished here
        if ($path === $file->path) {
            return;
        }

        $fileExisted = file_exists($projectDir.'/'.$path);

        $file->renameTo($path);

        if ($fileExisted) {
            // remove the double model
            $file->getModel()->delete();

            // recalculate hash (file content might have changed)
            \Dbafs::addResource($path);
        }

        $this->eventDispatcher->dispatch(AfterFilenameSanitizationEvent::NAME, new AfterFilenameSanitizationEvent($path));
    }

    public function sanitizeFolder(Folder $folder)
    {
        $projectDir = $this->containerUtil->getProjectDir();

        $event = $this->eventDispatcher->dispatch(BeforeFolderSanitizationEvent::NAME, new BeforeFolderSanitizationEvent($folder));

        /** @var Folder $folder */
        $folder = $event->getFolder();

        $parentFolder = str_replace($projectDir.'/', '', $folder->dirname);
        $sanitizedName = $this->sanitizeString($folder->basename);
        $path = $parentFolder.'/'.$sanitizedName;

        // if the folder's name already had been sane, we're finished here
        if ($path === $folder->path) {
            return;
        }

        if (file_exists($projectDir.'/'.$path)) {
            $folder->delete();
            Message::addError(sprintf($GLOBALS['TL_LANG']['ERR']['fileExists'], $sanitizedName));

            Controller::redirect($this->urlUtil->removeQueryString(['act', 'mode', 'rt']));
        }

        $folder->renameTo($path);

        $this->eventDispatcher->dispatch(AfterFolderSanitizationEvent::NAME, new AfterFolderSanitizationEvent($path));
    }
}
